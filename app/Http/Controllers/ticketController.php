<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\customerAddress;
use App\Models\DeclaredProducts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Ticket;
use App\Models\ticketAdditionalFees;
use App\Models\ticketPayments;
use App\Models\ticketProofOfPayment;
use App\Models\ticketShippingPayments;
use App\Models\ticketTrackingCode;
use App\Models\Settings;
use App\Models\WebhookData;
use App\Models\mediaComment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PHPShopify\ShopifySDK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendMail;


class ticketController extends Controller
{
    //Ticket Page
    public function ticket_index() {
        $customers = Customer::with(['DeclaredProducts', 'Ticket'])->get();  
    
        // Initialize an empty array to hold the customers and their tickets
        $customer_tickets = [];
        
        foreach ($customers as $customer) {
            // Group declared products by both shipping_method and request_method
            $groupedProducts = $customer->DeclaredProducts->groupBy(['shipping_method', 'request_method']);
            foreach ($groupedProducts as $shipping_method => $requestMethods) {
                foreach ($requestMethods as $request_method => $products) {
                    // Find the ticket for the specific shipping method
                    $customerTicket = $customer->Ticket->where('shipping_method', $shipping_method)->first();    
                    $ticket_id = $customerTicket ? $customerTicket->ticket_id : null;   
                    $order_id = $customerTicket ? $customerTicket->order_id : null;  // Fetch order_id from the customerTicket
                    $status = $customerTicket ? $customerTicket->status : null;  // Fetch status from the ticket  

                    $customer_tickets[] = [
                        'customer' => $customer,
                        'customer_shopify_id' => $customer->customer_id,
                        'ticket_id' => $ticket_id,  
                        'products' => $products,
                        'shipping_method' => $shipping_method,
                        'request_method' => $request_method,
                        'order_id' => $order_id,
                        'status' => $status,
                        'created_at' => $products->min('created_at') // Get the earliest creation date for sorting
                    ];
                }
            }
        } 
        
        // Sort the customer tickets by the created_at field
        usort($customer_tickets, function($a, $b) {
            return $a['created_at'] <=> $b['created_at'];
        });

        return view('tickets.ticket_index', ['customer_tickets' => $customer_tickets, 'customers' => $customers]);
    

    }
    
    
    //Ticket for Buying Assistance Page
    public function buyingAsssitance()
    {
        $customers = Customer::with(['DeclaredProducts', 'Ticket'])->get();
    
        // Initialize an empty array to hold the customers and their tickets
        $customer_tickets = [];
    
        foreach ($customers as $customer) {
            // Group declared products by shipping_method only
            $groupedProducts = $customer->DeclaredProducts->where('request_method', 'REQUEST_ESTIMATES')->groupBy('shipping_method');
            
            foreach ($groupedProducts as $shipping_method => $products) {
                // Find the ticket for the specific shipping method
                $customerTicket = $customer->Ticket->where('shipping_method', $shipping_method)->first();
                $ticket_id = $customerTicket ? $customerTicket->ticket_id : null;
                $order_id = $customerTicket ? $customerTicket->order_id : null;
                $status = $customerTicket ? $customerTicket->status : null;
    
                $customer_tickets[] = [
                    'customer' => $customer,
                    'customer_shopify_id' => $customer->customer_id,
                    'ticket_id' => $ticket_id,
                    'products' => $products,
                    'shipping_method' => $shipping_method,
                    'request_method' => 'REQUEST_ESTIMATES',
                    'order_id' => $order_id,
                    'status' => $status,
                    'created_at' => $products->min('created_at') // Get the earliest creation date for sorting
                ];
            }
        }
    
        // Sort the customer tickets by the created_at field
        usort($customer_tickets, function ($a, $b) {
            return $a['created_at'] <=> $b['created_at'];
        });
    
        return view('tickets.buying-assistance', ['customer_tickets' => $customer_tickets, 'customers' => $customers]);
    }
    

    
    //Ticket for Buying Assistance Page
    public function declaredShipment() { 
            $customers = Customer::with(['DeclaredProducts', 'Ticket'])->get();
        
            // Initialize an empty array to hold the customers and their tickets
            $customer_tickets = [];
        
            foreach ($customers as $customer) {
                // Group declared products by shipping_method only
                $groupedProducts = $customer->DeclaredProducts->where('request_method', 'DECLARE_SHIPMENTS')->groupBy('shipping_method');
                
                foreach ($groupedProducts as $shipping_method => $products) {
                    // Find the ticket for the specific shipping method
                    $customerTicket = $customer->Ticket->where('shipping_method', $shipping_method)->first();
                    $ticket_id = $customerTicket ? $customerTicket->ticket_id : null;
                    $order_id = $customerTicket ? $customerTicket->order_id : null;
                    $status = $customerTicket ? $customerTicket->status : null;
        
                    $customer_tickets[] = [
                        'customer' => $customer,
                        'customer_shopify_id' => $customer->customer_id,
                        'ticket_id' => $ticket_id,
                        'products' => $products,
                        'shipping_method' => $shipping_method,
                        'request_method' => 'DECLARE_SHIPMENTS',
                        'order_id' => $order_id,
                        'status' => $status,
                        'created_at' => $products->min('created_at') // Get the earliest creation date for sorting
                    ];
                }
            }
        
            // Sort the customer tickets by the created_at field
            usort($customer_tickets, function ($a, $b) {
                return $a['created_at'] <=> $b['created_at'];
            });
        
            return view('tickets.declared-shipment', ['customer_tickets' => $customer_tickets, 'customers' => $customers]); 
    }
    
     

    //Assign Ticket Page
    public function assign_ticket(Request $request, $customer_id, $ticket_id){ 

        $data = $request->all();  
        $shipping_method = $data['shipping_method'];
        $order_id = $data['order_id'];
        $request_method = $data['request_method'];
        $productIds = $data['product_ids']; // Get the product IDs that are submitted as an array 

        try {

            // Start a new database transaction
            DB::beginTransaction();

            $existing_ticket = Ticket::with([
                'Customer',
                'DeclaredProducts',
                'ticketAdditionalFees',
                'ticketNotes'
            ])->where('ticket_id', $ticket_id)->first();  

            // Get customer details with products and ticket
            $customer = Customer::with([
                'DeclaredProducts',
                'Ticket.ticketAdditionalFees',
                'Ticket.ticketNotes'
            ])->find($customer_id);

            // Generate or update the ticket ID
            $ticket_id = $this->generateTicketId($existing_ticket, $customer_id);  

            // Save generated ticket ID
            $ticket = new Ticket;
            $ticket->customer_id = $customer_id;
            $ticket->order_id = $order_id;
            $ticket->ticket_id = $ticket_id;
            $ticket->request_method = $request_method;
            $ticket->shipping_method = $shipping_method;
            $ticket->save();


            // Attach product IDs to the ticket
            $ticket->DeclaredProducts()->attach($productIds); // many to many

            // Commit the transaction if no error.
            DB::commit();
        
            return redirect()->route('view_ticket', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id])->with('success', 'Ticket assigned successfully');
            
        } catch (\Throwable $e) {

            // An error occurred. Rollback the transaction.
            DB::rollBack();

            // Log the error
            Log::error('Error saving ticket id: ' . $e->getMessage());

            // Return a response indicating an error occurred
            return back()->with(['error' => 'Error Saving Ticket ID']);

        } 
    } 


    //GENERATE TICKET ID
    private function generateTicketId($existing_ticket, $customer_id){
        $date_prefix = date('Ymd'); // This will give a string like "20240522" for May 22, 2024 
    
        if ($existing_ticket) {
            // If a ticket already exists, increment the ticket number
            $ticket_number = (int) substr($existing_ticket->ticket_id, -5); // Extract the numeric part of the existing ticket ID
        } else {
            // Generate a new ticket number
            $ticket_number = Ticket::where('customer_id', $customer_id)->count() + 1; // Get the count of existing tickets for the specific customer and add one to it
        }
    
        do {
            $new_ticket_number = $ticket_number++;
            $formatted_ticket_number = str_pad($new_ticket_number, 5, '0', STR_PAD_LEFT); // Format the ticket number to have 5 digits with leading zeros
            $ticket_id = $date_prefix . '_' . $formatted_ticket_number; // Construct the updated ticket ID 
        } while (Ticket::where('ticket_id', $ticket_id)->exists()); // Keep looping until a unique ticket_id is found
    
        return $ticket_id;
    } 


    //Open Ticket Page
    public function view_ticket($customer_id, $ticket_id) { 

        // Check if a ticket with the given customer_id already exists
        $existing_ticket = Ticket::with([
            'Customer',
            'DeclaredProducts',
            'ticketAdditionalFees',
            'ticketNotes',
            'ticketPayments',
            'ticketProofOfPayment'
        ])->where('ticket_id', $ticket_id)->first();  

        $customerAddress = CustomerAddress::where('customer_id', $customer_id)->first(); 

        // Collect all request_method values into an array
        $request_methods = $existing_ticket->DeclaredProducts->pluck('request_method')->all();
        $request_method = implode(',', $request_methods);  

        // Get the first ticket (if it exists) from the customer's tickets
        $firstTicket = $existing_ticket;
    
        // Retrieve all products associated with the specific ticket
        $products = $firstTicket ? $firstTicket->DeclaredProducts : collect(); // Use collect() to handle empty case 

        $admin_settings = Settings::first();  // Get admin settings

        $WebHookPaymentData = WebhookData::where('ticket_id', $ticket_id)->get(); //get data from webhook - if customer paid the initial payment request 

        $ticketMedia = ticketProofOfPayment::where('ticket_id', $ticket_id)->get(); //get the images saved in step 3 
        
        $mediaComments = mediaComment::where('ticket_id', $ticket_id)->get(); //get all the Media Comments for Step 3 

        $ticketShippingPayments = ticketShippingPayments::where('ticket_id', $ticket_id)->get(); //get all the Shipping Payments details - step 4

        $ticketTrackingCode = ticketTrackingCode::where('ticket_id', $ticket_id)->get(); //get all the Shipping Payments details - step 4

        if ($existing_ticket) {
            // If a ticket already exists, return the view with the existing ticket_id
            return view('tickets.view-ticket', [ 
                'ticket_id' => $ticket_id,
                'customer_id' => $customer_id,
                'customer_fname' => $existing_ticket->Customer->first_name,
                'firstTicket' => $firstTicket,
                'notes' => $existing_ticket->ticketNotes,
                'steps' => $existing_ticket->steps,
                'additonal_fees' => $existing_ticket->ticketAdditionalFees,
                'existing_ticket' => $existing_ticket,
                'products' => $products,
                'status' => $existing_ticket->status,
                'request_method' => $request_method, 
                'admin_settings' => $admin_settings,
                'ticketPayments' => $existing_ticket->ticketPayments->first(),
                'WebHookPaymentData' => $WebHookPaymentData,
                'customerAddress' => $customerAddress,
                'ticketMedia' => $ticketMedia,
                'mediaComments' => $mediaComments,
                'ticketShippingPayments' => $ticketShippingPayments,
                'ticketTrackingCode' => $ticketTrackingCode
            ]);
        } 
    }
    
  
    //Add products - Ticket Page
    public function addProducts(Request $request, $customer_id){ 

       try {
        
            // Start a database transaction
            DB::beginTransaction();

            // Validate the request
            $request->validate([
                'product_name' => 'required|string|max:255',
                'product_link' => 'required|url',
                'product_qty' => 'required|integer|min:1',
                'product_variant' => 'nullable|string|max:255', 
                'shipping_method' => 'nullable|string|max:255', 
                'request_method' => 'nullable|string|max:255', 
            ]);

            // Get validated data
            $data = $request->all();  

            // Create a new product and save it to the database
            $addProduct = new DeclaredProducts;
            $addProduct->customer_id = $customer_id; 
            $addProduct->product_name = $data['product_name'];  
            $addProduct->product_link = $data['product_link']; 
            $addProduct->product_qty = $data['product_qty'];  
            $addProduct->product_variant = $data['product_variant'];  
            $addProduct->shipping_method = strtolower($data['shipping_method']);  
            $addProduct->request_method = $data['request_method'];  
            $addProduct->save(); // Save the data

            // Attach product IDs to the ticket
            $ticket = Ticket::where('ticket_id', $data['ticket_id'])->first(); // Get the ticket instance
            if($ticket) {
                $ticket->declaredProducts()->attach($addProduct->id); // Attach the product to the ticket
            }


            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Product added successfully'); // Redirect back with a success message


       } catch (\Throwable $e) {
        
            // Rollback the transaction
            DB::rollBack();

            // Log the error for debugging
            \Log::error('Failed to add product: ' . $e->getMessage());
    
            // Redirect back with an error message
            return redirect()->back()->with('error', 'Failed to add product. Please try again.');
        }

    }


    //Delete products - Ticket Page
    public function deleteProducts($customer_id, $product_id){ 
 
        // Find the product by ID
        $product = DeclaredProducts::findOrFail($product_id);

        // Delete the product
        $product->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Product deleted successfully.'); 
 
    }



    
    // STEP 1: Initial Payment 
    public function initialPayment(Request $request) {     

        try {
            // Validate the request
            $validatedData = $request->validate([
                'customer_id' => 'required|string|max:255',
                'ticket_id' => 'required|string|max:255',
                'steps' => 'required|integer|min:1',
                'totalCreditCardFee' => 'nullable',
                'totalHandlingFee' => 'nullable',
                'totalCustomTax' => 'nullable',
                'totalConvenienceFee' => 'nullable',
                'productTotalValue' => 'required',
                'productValue' => 'required',
                'product_qty' => 'nullable|integer|min:1',
                'status' => 'nullable|string|max:255',
                'payment_type' => 'nullable|string|max:255',
                'customer_fname' => 'required|string|max:255', 
                'requestEstimateFile' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'email_type' => 'string|max:255',
            ]);

            // Check if the file is uploaded
            if ($request->hasFile('requestEstimateFile')) {
                $image = $validatedData['requestEstimateFile'];
                $imageName = time() . '.' . $image->extension();
                $path = $image->store('public/images/request-estimate'); 

                // Remove 'public/' from the path
                $path = str_replace('public/', '', $path);

                // Create a URL for the image
                $imageUrl = config('app.url') . Storage::url($path);
                Log::info('image URL: ' . $imageUrl);

            } else {
                // Handle the case where the file is not uploaded (if needed)
                $path = null;
            }
 

            //Create product for customer initial payment request
            $shopify = new ShopifySDK();

            $customerDetails = Customer::where('id', $validatedData['customer_id'])->first(); 

            $productData = [
                'title' => 'Initial Payment for ' . $validatedData["customer_fname"],
                'body_html' => 'This is a product for initial payment request for ' . $validatedData["customer_fname"],
                'variants' => [
                    [
                        'price' => $validatedData['productTotalValue'], // Set price based on request
                        'inventory_management' => 'shopify', // Enable Shopify inventory management
                        'inventory_policy' => 'deny', // Deny when out of stock
                        'fulfillment_service' => 'manual', // Manual fulfillment service
                        'sku' => 'initial-payment-' . $validatedData["customer_id"], // Add a SKU for tracking
                        'inventory_quantity' => 1, // Set the quantity to 1
                        'requires_shipping' => false, // No shipping required
                    ],
                ],
                'images' => [
                    [
                        // 'src' => 'https://cdn.shopify.com/s/files/1/0637/7789/8668/files/req_payment.jpg?v=1717450246',
                        'src' => $imageUrl, // Use the uploaded image URL
                    ],
                ],
                 
            ];
            
            // Create the product
            $createdProduct = $shopify->Product->post($productData);

            // Generate the product URL
            $productUrl = 'https://californila-v2.myshopify.com/products/' . $createdProduct['handle'];

            $collectionId = '302621229228'; //collection ID for "Initial Payment Request"

            // Associate the product with the collection
            $collectData = [
                'product_id' => $createdProduct['id'],
                'collection_id' => $collectionId,
            ];

            $createdCollect = $shopify->Collect->post($collectData); //Assign the newly created product to "Initial Payment Request" collection
             
            //Create Order
            $orderData = [
                'line_items' => [
                    [
                        'variant_id' => $createdProduct['variants'][0]['id'],
                        'quantity' => 1,
                    ],
                ],
                'customer' => [
                    'email' => $customerDetails->email, // Customer's email address 
                ],
                'financial_status' => 'pending', // Set to 'pending' or 'on_hold'
                'note' => $validatedData['ticket_id'], // Custom note here | Store the ticket_id here
            ];
            
            $createdDraftOrder = $shopify->DraftOrder->post($orderData); //create order for newly added product
            $payNowLink = $createdDraftOrder['invoice_url']; // This is your "Pay Now" link
            $base_url = '127.0.0.1/storage/';
            $imagePath = str_replace($base_url, '', $imageUrl); 

            // Start a database transaction
            DB::beginTransaction();

            // Create a new Ticket Payment data and save it to the database
            $ticketPayment = new TicketPayments($validatedData);
            $ticketPayment->ticket_id = $validatedData['ticket_id'];
            $ticketPayment->total_handling_fee = $validatedData['totalHandlingFee'];
            $ticketPayment->total_custom_tax = $validatedData['totalCustomTax'];
            $ticketPayment->total_convenience_fee = $validatedData['totalConvenienceFee'];
            $ticketPayment->total_credit_card_fee = $validatedData['totalCreditCardFee'];
            $ticketPayment->total_product_value = $validatedData['productValue'];
            $ticketPayment->total_product_price = $validatedData['productTotalValue'];
            $ticketPayment->payment_type = $validatedData['payment_type'];
            $ticketPayment->shopify_product_ip_id = $createdProduct['id'];
            $ticketPayment->image_path = $path; //image
            $ticketPayment->save();

            // Update Ticket data and save it to the database
            $ticketUpdate = Ticket::where('ticket_id', $validatedData['ticket_id'])->first();
            $ticketUpdate->status = 'pendingPayment';
            $ticketUpdate->steps = 2;
            $ticketUpdate->save();

            // Commit the transaction
            DB::commit();
            
            // After saving the validatedData to database, send the mail
            $getCustomerIdFromTicket = Ticket::where('ticket_id', $validatedData['ticket_id'])->get(); // Get Customer ID form Ticket
            $getCustomer = Customer::where('id', $getCustomerIdFromTicket->first()->customer_id)->get(); // Replace with the customer's email address  
            $customerEmail = $getCustomer->first()->email;

            // Check if a ticket with the given customer_id already exists
            $existing_ticket = Ticket::with(['DeclaredProducts'])->where('ticket_id', $validatedData['ticket_id'])->first();

            // Retrieve all products associated with the specific ticket
            $products = $existing_ticket ? $existing_ticket->DeclaredProducts : collect(); // Use collect() to handle empty case

            // Add product details to the email data
            $data = array_merge($validatedData, [
                'product_url' => $productUrl,
                'image_url' => $imagePath,
                // 'image_url' => $imageUrl,
                'products' => $products,
                'payNowLink' => $payNowLink
            ]);  

            Mail::to($customerEmail)->send(new sendMail($data));
            

            return redirect()->back()->with('success', 'Product for payment created successfully.');
        } catch (\Exception $e) {
            // Handle exceptions (log, rollback, etc.)
            DB::rollback();
            Log::error('Error in initialPayment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred. Please try again later.');
        }

    } 


    // PROCEED TO STEP 3: ADD PROOF/MEDIA/IMAGES
    public function step_3($customer_id, $ticket_id){ 

        // Save the image path to the database
        $stepUpdate = Ticket::where('ticket_id', $ticket_id)->first();
        $stepUpdate->status = 'addingMedia'; //UPDATE STATUS
        $stepUpdate->steps = 3; //UPDATE STEP
        $stepUpdate->save();
   
        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }

    // STEP 3: ADD PROOF/MEDIA/IMAGES
    public function uploadFiles(Request $request, $customer_id, $ticket_id){ 

        $request->validate([ 
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $image = $request->file('file');
        $imageName = time().'.'.$image->extension();
        $path = $image->store('public/images');

        // Remove 'public/' from the path
        $path = str_replace('public/', '', $path);

        // Save the image path to the database
        $ticketProofOfPayment = new ticketProofOfPayment();
        $ticketProofOfPayment->ticket_id = $ticket_id; // Use parameter value
        $ticketProofOfPayment->image_path = $path; // Save the correct path
        $ticketProofOfPayment->save();
   
        return response()->json(['success'=>$imageName]);
    }

    // STEP 3: DELETE MEDIA
    public function deleteFiles($customer_id, $ticket_id, $image_id){
        
        try {

            // Assuming you have an 'Image' model with an 'image_path' field
            $image = ticketProofOfPayment::findOrFail($image_id);

            // Get the full path to the image  
            $image_path = public_path('storage/' . $image->image_path);

            if (File::exists($image_path)) {
                // Delete the image file
                File::delete($image_path);

                // Optionally, update your database to remove the image record
                $image->delete();

                return redirect()->back()->with('success', 'Image deleted successfully.');
            } else {
                return redirect()->back()->with('error', 'Image not found.');
            }

        } catch (\Exception $e) { 

            // Log the error for debugging purposes
            Log::error('Error deleting media:', ['exception' => $e]);

            return redirect()->back()->with('error', 'Error deleting image. Please try again later.');
        }

    }

    // STEP 3: Sending media comment to customer
    public function mediaComment(Request $request, $customer_id, $ticket_id){  

        // Validate the request data
        $request->validate([
            'mediaComment' => 'required|string|max:1000',
            'uploaded_images' => 'array',
            'uploaded_images.*' => 'string|max:255', // Assuming the image paths are stored as strings
        ]);

        // Get data request
        $data = $request->all();
    
        $comment = $data['mediaComment'];
        $uploadedImages = $data['uploaded_images'] ?? [];         
    
        // Create a new Media Comment and save it to the database
        $mediaComment = new mediaComment;
        $mediaComment->ticket_id = $ticket_id; 
        $mediaComment->comment = $comment;  
        $mediaComment->save(); // Save the data 

        // After saving the comment, send the mail to customer
        $customerEmail = 'jan@ishkaster.com'; // Replace with the customer's email address
        Mail::to($customerEmail)->send(new sendMail($data));
    
        return redirect()->back()->with('success', 'Comment and media sent successfully!');
    }
 

    // PROCEED TO STEP 4: SHIPPING PAYMENT
    public function step_4($customer_id, $ticket_id){ 

        // Save the image path to the database
        $stepUpdate = Ticket::where('ticket_id', $ticket_id)->first();
        $stepUpdate->status = 'shippingPayment'; //UPDATE STATUS
        $stepUpdate->steps = 4; //UPDATE STEP
        $stepUpdate->save();
    
        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }


    // STEP 4: SHIPPING PAYMENT
    public function shippingPayment(Request $request){ 
        
        try {
            // Validate the request
            $validatedData = $request->validate([ 
                'ticket_id' => 'required|string|max:255',
                'shipping_value' => 'required', 
                'requestShippingEstimateFile' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'customer_fname' => 'required|string|max:255', 
                'customer_id' => 'required|string|max:255',
                'email_type' => 'string|max:255',

            ]);

            // Check if the file is uploaded
            if ($request->hasFile('requestShippingEstimateFile')) {
                $image = $validatedData['requestShippingEstimateFile'];
                $imageName = time() . '.' . $image->extension();
                $path = $image->store('public/images/request-shipping-estimate'); 

                // Remove 'public/' from the path
                $path = str_replace('public/', '', $path);

                // Create a URL for the image
                $imageUrl = config('app.url') . Storage::url($path);
                Log::info('image URL: ' . $imageUrl);

            } else {
                // Handle the case where the file is not uploaded (if needed)
                $path = null;
            }


            //Create product for customer Shipping payment request
            $shopify = new ShopifySDK();

            $customerDetails = Customer::where('id', $validatedData['customer_id'])->first(); 

            $productData = [
                'title' => 'Shipping Payment for ' . $validatedData["customer_fname"],
                'body_html' => 'This is a product for Shipping payment request for ' . $validatedData["customer_fname"],
                'variants' => [
                    [
                        'price' => $validatedData['shipping_value'], // Set shipping price based on request
                        'inventory_management' => 'shopify', // Enable Shopify inventory management
                        'inventory_policy' => 'deny', // Deny when out of stock
                        'fulfillment_service' => 'manual', // Manual fulfillment service
                        'sku' => 'shipping-payment-' . $validatedData["customer_id"], // Add a SKU for tracking
                        'inventory_quantity' => 1, // Set the quantity to 1
                        'requires_shipping' => false, // No shipping required
                    ],
                ],
                'images' => [
                    [
                        // 'src' => 'https://cdn.shopify.com/s/files/1/0637/7789/8668/files/req_payment.jpg?v=1717450246',
                        'src' => $imageUrl, // Use the uploaded image URL
                    ],
                ],
                 
            ];
            
            // Create the product
            $createdProduct = $shopify->Product->post($productData);

            // Generate the product URL
            $productUrl = 'https://californila-v2.myshopify.com/products/' . $createdProduct['handle'];

            $collectionId = '302621229228'; //collection ID for "Initial Payment Request"

            // Associate the product with the collection
            $collectData = [
                'product_id' => $createdProduct['id'],
                'collection_id' => $collectionId,
            ];

            $createdCollect = $shopify->Collect->post($collectData); //Assign the newly created product to "Initial Payment Request" collection

            //Create Order
            $orderData = [
                'line_items' => [
                    [
                        'variant_id' => $createdProduct['variants'][0]['id'],
                        'quantity' => 1,
                    ],
                ],
                'customer' => [
                    'email' => $customerDetails->email, // Customer's email address 
                ],
                'financial_status' => 'pending', // Set to 'pending' or 'on_hold'
                'note' => $validatedData['ticket_id'], // Custom note here | Store the ticket_id here
            ];
            
            $createdDraftOrder = $shopify->DraftOrder->post($orderData); //create order for newly added product
            $payNowLink = $createdDraftOrder['invoice_url']; // This is your "Pay Now" link
            $base_url = '127.0.0.1/storage/';
            $imagePath = str_replace($base_url, '', $imageUrl); 

            // Start a database transaction
            DB::beginTransaction();

            // Create a new Ticket Shipping Payment data and save it to the database
            $ticketShippingPayment = new ticketShippingPayments($validatedData);
            $ticketShippingPayment->ticket_id = $validatedData['ticket_id']; 
            $ticketShippingPayment->shopify_product_sp_id = $createdProduct['id']; 
            $ticketShippingPayment->total_shipping_value = $validatedData['shipping_value']; 
            $ticketShippingPayment->image_path = $path; //image
            $ticketShippingPayment->save();

            // Update Ticket data and save it to the database
            $ticketUpdate = Ticket::where('ticket_id', $validatedData['ticket_id'])->first();
            $ticketUpdate->status = 'pendingShippingPayment';
            $ticketUpdate->steps = 5;
            $ticketUpdate->save();

            // Commit the transaction
            DB::commit();

            // After saving the validatedData to database, send the mail
            $getCustomerIdFromTicket = Ticket::where('ticket_id', $validatedData['ticket_id'])->get(); // Get Customer ID form Ticket
            $getCustomer = Customer::where('id', $getCustomerIdFromTicket->first()->customer_id)->get(); // Replace with the customer's email address  
            $customerEmail = $getCustomer->first()->email;

            // Check if a ticket with the given customer_id already exists
            $existing_ticket = Ticket::with(['DeclaredProducts'])->where('ticket_id', $validatedData['ticket_id'])->first();

            // Retrieve all products associated with the specific ticket
            $products = $existing_ticket ? $existing_ticket->DeclaredProducts : collect(); // Use collect() to handle empty case

            // Add product details to the email data
            $data = array_merge($validatedData, [
                'product_url' => $productUrl,
                'image_url' => $imagePath,
                // 'image_url' => $imageUrl,
                'products' => $products,
                'payNowLink' => $payNowLink
            ]);  

            Mail::to($customerEmail)->send(new sendMail($data));


            return redirect()->back()->with('success', 'Product for shipping payment created successfully.');

        } catch (\Exception $e) {

            // Handle exceptions (log, rollback, etc.)
            DB::rollback();
            Log::error('Error in shippingPayment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred. Please try again later.');
            
        }

    }

    // STEP 1 & 5: Payment Checker - SHOPIFY WEBHOOK 
    public function PaymentChecker(Request $request) {
        $data = $request->all();
        Log::info('Full webhook data:', $data);
    
        // Extract the customer_id and note (which you use as ticket_id) from the data
        $customerId = isset($data['customer']['id']) ? $data['customer']['id'] : null;
        $note = isset($data['note']) ? $data['note'] : 'No note provided';
        $shopify_product_id = isset($data['line_items'][0]['product_id']) ? $data['line_items'][0]['product_id'] : null;
        $shopify_order_id = isset($data['id']) ? $data['id'] : null;
        $shopify_product_name = isset($data['line_items'][0]['name']) ? $data['line_items'][0]['name'] : null;

        // Determine the type of payment based on the product name
        $paymentType = '';
        if ($shopify_product_name) {
            if (strpos($shopify_product_name, 'Initial Payment') !== false) {
                $paymentType = 'initial-payment';
            } elseif (strpos($shopify_product_name, 'Shipping Payment') !== false) {
                $paymentType = 'shipping-payment';
            }
        }
    
        try {
            // Start a database transaction
            DB::beginTransaction();
    
            // Store the data in the database using the model
            WebhookData::create([
                'customer_id' => $customerId,
                'ticket_id' => $note,
                'shopify_order_id' => $shopify_order_id,
                'payment_type' => $paymentType,
                'shopify_product_id' => $shopify_product_id,
                'data' => json_encode($data),
            ]);

            $ticketUpdate = Ticket::where('ticket_id', $note)->first();
            $ticketUpdate->status = 'shippingPaymentPaid'; 
            $ticketUpdate->save();
    
            // Commit the transaction
            DB::commit();

        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();
    
            // Log the exception
            Log::error('Error saving webhook data: ' . $e->getMessage(), ['exception' => $e]); 
    
            // Respond with a failure status (4XX or 5XX) to acknowledge the error
            return response()->json(['success' => false, 'message' => 'Error processing webhook'], 500);
        }
    
        // Respond with a success status (2XX) to acknowledge the webhook
        return response()->json(['success' => true]);
    }


    // PROCEED TO STEP 6: ADDING TICKET TRACKING
    public function step_6($customer_id, $ticket_id){ 

        // Save the image path to the database
        $stepUpdate = Ticket::where('ticket_id', $ticket_id)->first();
        $stepUpdate->status = 'addingTracking'; //UPDATE STATUS
        $stepUpdate->steps = 6; //UPDATE STEP
        $stepUpdate->save();
    
        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }

    public function addTrackingCode(Request $request, $customer_id, $ticket_id){ 

        try {  
            // Validate the request
            $validatedData = $request->validate([ 
                'ticket_id' => 'required|string|max:255', 
                'tracking_code' => 'string|max:300', 
                'tracking_link' => 'string|max:300',  
                'customer_fname' => 'required|string|max:255', 
                'email_type' => 'string|max:255',
            ]);    

            // Start a database transaction
            DB::beginTransaction();

            // Create a new Ticket Tracking Code data and save it to the database
            $addTrackingCode = new ticketTrackingCode($validatedData);
            $addTrackingCode->ticket_id = $validatedData['ticket_id']; 
            $addTrackingCode->tracking_code = $validatedData['tracking_code']; 
            $addTrackingCode->tracking_link = $validatedData['tracking_link'];  
            $addTrackingCode->save();

            // Update Ticket data and save it to the database
            $ticketUpdate = Ticket::where('ticket_id', $validatedData['ticket_id'])->first();
            $ticketUpdate->status = 'trackingCodeAdded'; 
            $ticketUpdate->save();

            // Commit the transaction
            DB::commit(); 

            // After saving the validatedData to database, send the mail
            $getCustomerIdFromTicket = Ticket::where('ticket_id', $validatedData['ticket_id'])->get(); // Get Customer ID form Ticket
            $getCustomer = Customer::where('id', $getCustomerIdFromTicket->first()->customer_id)->get(); // Replace with the customer's email address  
            $customerEmail = $getCustomer->first()->email;

            $data = $validatedData;

            Mail::to($customerEmail)->send(new sendMail($data));

            return redirect()->back()->with('success', 'Tracking code successfully.');

        } catch (\Exception $e) {

            // Handle exceptions (log, rollback, etc.)
            DB::rollback();
            Log::error('Error in addTrackingCode: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred. Please try again later.'); 
            
        }

    }

    // PROCEED TO STEP 7: ADDING TICKET TRACKING
    public function step_7($customer_id, $ticket_id){ 

        // Save the image path to the database
        $stepUpdate = Ticket::where('ticket_id', $ticket_id)->first();
        $stepUpdate->status = 'confirmClosingTicket'; //UPDATE STATUS
        $stepUpdate->steps = 7; //UPDATE STEP
        $stepUpdate->save();
    
        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }


    // STEP 7: CLOSING TICKET
    public function closeTicket(Request $request){  

        // Validate the request
        $data = $request->validate([ 
            'ticket_id' => 'required|string|max:255', 
            'customer_fname' => 'required|string|max:255', 
            'customer_id' => 'required|string|max:255',
            'email_type' => 'string|max:255',

        ]);

        // Save the image path to the database
        $ticketClose = Ticket::where('ticket_id', $data['ticket_id'])->first();
        $ticketClose->status = 'closeTicket'; //UPDATE STATUS
        $ticketClose->steps = 8; //UPDATE STEP
        $ticketClose->save();

        // After saving the data to database, send the mail
        $getCustomerIdFromTicket = Ticket::where('ticket_id', $data['ticket_id'])->get(); // Get Customer ID form Ticket
        $getCustomer = Customer::where('id', $getCustomerIdFromTicket->first()->customer_id)->get(); // Replace with the customer's email address  
        $customerEmail = $getCustomer->first()->email;

        Mail::to($customerEmail)->send(new sendMail($data));
    
        return to_route('solvedTicketsView')->with('success', 'Ticket updated successfully.'); 

    }


    // SOLVED TICKETS VIEW
    public function solvedTicketsView(){

        $solvedTickets = Ticket::with(['DeclaredProducts', 'customer'])->where('status', 'closeTicket')->get();  
        return view('tickets.solved-tickets', ['solvedTickets' => $solvedTickets]);
    }
    
}
