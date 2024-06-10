<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            //EMAIL FOR NOTE
            case 'emailNote':
                return '<div class="card-header"> <h3>Hi ' . e($data['customer_first_name']) . ', new note has been added to your ticket#: ' . e($data['ticket_id']) . '</h3> </div> <div class="card-body"> <h4>NOTE:</h4> <p>' . e($data['ticket_note']) . '</p> </div>';
            
            //EMAIL FOR ADDITIONAL FEE
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

                // EMAIL FOR MEDIA COMMENT: STEP 3
                case 'emailMediaComment':
                    $imageCount = count($data['uploaded_images']);
                    return '<div class="card-header">
                    <h3>Hello ' . e($data['customer_first_name']) . ', a new comment has been added to your ticket#: ' . e($data['ticket_id']) . '</h3> 
                    </div>
                    <div class="card-body" style="margin-bottom:20px;">
                        <div class="comment_container" style="display: flex; align-items: center;">
                            <h4 style="margin:0;">Comment:</h4> <span style="margin-left:10px;">' . e($data['mediaComment']) . '</span>
                        </div> 
                        <div class="images_container" style="display: flex; align-items: center;">
                            <h4 style="margin:0;">Please see attached images:</h4> <span style="margin-left:10px;">' . $imageCount . ' image(s) attached</span>
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
    $subject = '';
        switch ($this->data['email_type']) {
            case 'emailNote':
                $subject = 'New notes added to your ticket';
                break;
            case 'emailFee':
                $subject = 'Additional fees added to your ticket';
                break;
            case 'emailMediaComment':
                $subject = 'New comment added to your ticket';
                break;
            default:
                $subject = 'Update on your ticket';
                break;
        }

        return new Envelope(
            subject: $subject,
        );
    }
 


    /**
     * Build the message.
     */
    public function build()
    {
        $email = $this->from('janluigieflores@gmail.com', 'Californila App')
                      ->markdown('emails.sendMail');

        if ($this->data['email_type'] == 'emailMediaComment' && isset($this->data['uploaded_images'])) {
        foreach ($this->data['uploaded_images'] as $image) {
            $path = public_path('storage/' . $image);
            if (file_exists($path)) {
                $email->attach($path); 
            } else {
                // Log an error message
                \Log::error("File does not exist at path: " . $path);
            }
        }
    }

        return $email;
    }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         markdown: 'emails.sendMail',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     $attachments = [];
    //     if ($this->data['email_type'] == 'emailMediaComment' && isset($this->data['uploaded_images'])) {
    //         foreach ($this->data['uploaded_images'] as $image) {
    //             $attachments[] = new \Illuminate\Mail\Mailables\Attachment($image, basename($image));
    //         }
    //     }
    //     return $attachments;
    // }


}
