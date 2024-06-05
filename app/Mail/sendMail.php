<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; //set the data here

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        //pass the $data here
        $this->data = $data;
        $this->data['emailContent'] = $this->emailType($data);
    }

    public function emailType($data) { 

        switch ($data['email_type']) {
            case 'emailNote':
                return '<div class="card-header"> <h3>Hi ' . e($data['customer_first_name']) . ', new note has been added to your ticket#: ' . e($data['ticket_id']) . '</h3> </div> <div class="card-body"> <h4>NOTE:</h4> <p>' . e($data['ticket_note']) . '</p> </div>';
            
            case 'emailFee':
                return '<div class="card-header">
                <h3>Hi ' . e($data['customer_first_name']) . ', additional fees has been added to your ticket#: ' . e($data['ticket_id']) . '</h3> 
                </div>
                <div class="card-body" style="margin-bottom:20px;">
                    <div class="fee_container" style="display: flex; align-items: center;">
                        <h4 style="margin:0;">Fee:</h4> <span style="margin-left:10px;">₱ ' . e($data['amount']) . '</span>
                    </div> 
                    <div class="purpose_container" style="display: flex; align-items: center;">
                        <h4 style="margin:0;">Purpose:</h4> <span style="margin-left:10px;">' . e($data['fee_data_details']) . '</span>
                    </div> 
                </div>';
    
            default:
                return '';
        }
    }
    

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Set the subject based on the email type
        $subject = $this->data['email_type'] == 'emailNote' ? 'New notes added to your ticket' : 'Additional fees added to your ticket';
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.sendMail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
