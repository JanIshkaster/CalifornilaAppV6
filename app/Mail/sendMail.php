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
        $this->data = $data; 
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

                // EMAIL INITIAL PAYMENT: STEP 1
                case 'initialPayment': 
                    $productsHtml = '';
                    foreach ($data['products'] as $product) {
                        $productsHtml .= '<tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" style="border-bottom: 1px solid #e5e7eb;">
                                            <td class="px-6 py-4" style="padding: 8px 16px;">' . e($product->product_name) . '</td>
                                            <td class="px-6 py-4" style="padding: 8px 16px;">' . e($product->product_qty) . '</td>
                                            <td class="px-6 py-4" style="padding: 8px 16px;">' . e($product->product_variant) . '</td>
                                            <td class="px-6 py-4" style="padding: 8px 16px;">
                                                <a href="' . e($product->product_link) . '" class="text-blue-600 no-underline bg-transparent" target="_blank" rel="noopener noreferrer" style="color: #3b82f6; text-decoration: none;">
                                                    <span class="mdi mdi-link"></span> Link
                                                </a>
                                            </td>
                                        </tr>';
                    }
                
                    return '<div class="card-header">
                                    <h3>Hello ' . e($data['customer_fname']) . ', here is the requested payment for your ticket#: ' . e($data['ticket_id']) . '</h3> 
                                </div>
                                <div class="card-body" style="margin-bottom:20px;"> 
                                    <div class="products_container" style="margin-top: 20px;">
                                        <h4 style="margin: 0;">Please checkout this generated order: ' . e($data['payNowLink']) .'</h4> 
                                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mt-4" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400" style="background-color: #f9fafb;">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3" style="padding: 8px 16px; text-align: left;">Product</th>
                                                        <th scope="col" class="px-6 py-3" style="padding: 8px 16px; text-align: left;">Qty</th>
                                                        <th scope="col" class="px-6 py-3" style="padding: 8px 16px; text-align: left;">Variation</th>
                                                        <th scope="col" class="px-6 py-3" style="padding: 8px 16px; text-align: left;">Link</th> 
                                                    </tr>
                                                </thead>
                                            <tbody>' . $productsHtml . '</tbody>
                                        </table>
                                    </div>
                                    <div class="desc_container" style="display: flex; align-items: center;">
                                        <h4 style="margin:0;">Please see attached file for the invoice breakdown:</h4>  
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


                // EMAIL SHIPPING PAYMENT: STEP 4
                case 'shippingPayment': 
                    $productsHtml = '';
                    foreach ($data['products'] as $product) {
                        $productsHtml .= '<tr class="border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" style="border-bottom: 1px solid #e5e7eb;">
                                            <td class="px-6 py-4" style="padding: 8px 16px;">' . e($product->product_name) . '</td>
                                            <td class="px-6 py-4" style="padding: 8px 16px;">' . e($product->product_qty) . '</td>
                                            <td class="px-6 py-4" style="padding: 8px 16px;">' . e($product->product_variant) . '</td>
                                            <td class="px-6 py-4" style="padding: 8px 16px;">
                                                <a href="' . e($product->product_link) . '" class="text-blue-600 no-underline bg-transparent" target="_blank" rel="noopener noreferrer" style="color: #3b82f6; text-decoration: none;">
                                                    <span class="mdi mdi-link"></span> Link
                                                </a>
                                            </td>
                                        </tr>';
                    }
                
                    return '<div class="card-header">
                                    <h3>Hello ' . e($data['customer_fname']) . ', here is the requested payment for your ticket#: ' . e($data['ticket_id']) . '</h3> 
                                </div>
                                <div class="card-body" style="margin-bottom:20px;"> 
                                    <div class="products_container" style="margin-top: 20px;">
                                        <h4 style="margin: 0;">Please checkout this generated order: ' . e($data['payNowLink']) .'</h4> 
                                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mt-4" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400" style="background-color: #f9fafb;">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3" style="padding: 8px 16px; text-align: left;">Product</th>
                                                        <th scope="col" class="px-6 py-3" style="padding: 8px 16px; text-align: left;">Qty</th>
                                                        <th scope="col" class="px-6 py-3" style="padding: 8px 16px; text-align: left;">Variation</th>
                                                        <th scope="col" class="px-6 py-3" style="padding: 8px 16px; text-align: left;">Link</th> 
                                                    </tr>
                                                </thead>
                                            <tbody>' . $productsHtml . '</tbody>
                                        </table>
                                    </div>
                                    <div class="desc_container" style="display: flex; align-items: center;">
                                        <h4 style="margin:0;">Please see attached file for the invoice breakdown:</h4>  
                                    </div> 
                                </div>';


                // EMAIL ADDING TRACKING CODE: STEP 6
                case 'trackingCode': 
                    return '<div class="card-header">
                    <h3>Hello ' . e($data['customer_fname']) . ', Tracking code added to your ticket#: ' . e($data['ticket_id']) . '</h3> 
                    </div>
                    <div class="card-body" style="margin-bottom:20px;">
                        <div class="container" style="display: flex; align-items: center;">
                            <h4 style="margin:0;">Tracking Code:</h4> <span style="margin-left:10px;">' . e($data['tracking_code']) . '</span>
                        </div> 
                        <div class="container" style="display: flex; align-items: center;">
                            <h4 style="margin:0;">Tracking Link:</h4> <span style="margin-left:10px;">' . e($data['tracking_link']) . '</span>
                        </div> 
                    </div>'; 


                // EMAIL CLOSING TICKET: STEP 7
                case 'closeTicket': 
                    return '<div class="card-header">
                    <h3>Hello ' . e($data['customer_fname']) . ',</h3> 
                    </div>
                    <div class="card-body" style="margin-bottom:20px;">
                        <div class="container" style="display: flex; align-items: center;">
                            <h4 style="margin:0;">
                                Thank you for being our valued customer. We are pleased to inform you that your product/items are on the way to you. 
                                We hope our services will meet your expectations. Rest assured, we are here to support you every step of the way. Let us know if you have any questions.
                            </h4> 
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
                $subject = 'New notes added to your ticket#: ' . e($this->data['ticket_id']);
                break;
            case 'emailFee':
                $subject = 'Additional fees added to your ticket#: ' . e($this->data['ticket_id']);
                break;
            case 'emailMediaComment':
                $subject = 'New comment added to your ticket#: ' . e($this->data['ticket_id']);
                break;
            case 'initialPayment':
                $subject = 'Initial Payment Request for your ticket#: ' . e($this->data['ticket_id']);
                break;
            case 'trackingCode':
                $subject = 'Tracking code added to your ticket#: ' . e($this->data['ticket_id']);
                break;
            case 'closeTicket':
                $subject = 'Your Order is on its way! ticket#: ' . e($this->data['ticket_id']);
                break;
            default:
                $subject = 'Update on your ticket#: ' . e($this->data['ticket_id']);
                break;
        }
    
        return new Envelope(
            subject: $subject,
        );
    }
    
 


    /**
     * Build the message.
     */
    public function build() {

        $this->data['emailContent'] = $this->emailType($this->data);

        $email = $this->from('janluigieflores@gmail.com', 'Californila App')
                      ->markdown('emails.sendMail');

        // Manually add CC
        // $email->cc('cc@example.com'); // Replace with the desired CC address

        // Manually add BCC
        $email->bcc('jan@ishkaster.com'); // Replace with the desired BCC address

        // FOR INITIAL PAYMENT: STEP 1
        if ($this->data['email_type'] == 'initialPayment') {
            $images = is_array($this->data['image_url']) ? $this->data['image_url'] : [$this->data['image_url']];
            foreach ($images as $image) {
                $path = public_path('storage/' . $image);
                if (file_exists($path)) {
                    $email->attach($path); 
                } else {
                    // Log an error message
                    \Log::error("FOR INITIAL PAYMENT: STEP 1: File does not exist at path: " . $path);
                }
            }
        }

        

        //FOR MEDIA UPLOAD: STEP 3
        if ($this->data['email_type'] == 'emailMediaComment' && isset($this->data['uploaded_images'])) {
            foreach ($this->data['uploaded_images'] as $image) {
                $path = public_path('storage/' . $image);
                if (file_exists($path)) {
                    $email->attach($path); 
                } else {
                    // Log an error message
                    \Log::error("FOR MEDIA UPLOAD: STEP 3: File does not exist at path: " . $path);
                }
            }

        }

        // FOR SHIPPING PAYMENT: STEP 4
        if ($this->data['email_type'] == 'shippingPayment') {
            $images = is_array($this->data['image_url']) ? $this->data['image_url'] : [$this->data['image_url']];
            foreach ($images as $image) {
                $path = public_path('storage/' . $image);
                if (file_exists($path)) {
                    $email->attach($path); 
                } else {
                    // Log an error message
                    \Log::error("FOR SHIPPING PAYMENT: STEP 4: File does not exist at path: " . $path);
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
