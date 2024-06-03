<div class="md:flex w-full mt-5" id="timeline">
    <ul class="flex-column space-y space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0">
        <li>
            <a href="#" id="step_1_tab" data-step="1"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white"
                aria-current="page">
                <div class="title"><span class="step-number">1</span>
                    <span class="step-text">Initial Payment</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_2_tab" data-step="2"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">2</span>
                    <span class="step-text">Approve Payment</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_3_tab" data-step="3"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">3</span>
                    <span class="step-text">Add Pictures</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_4_tab" data-step="4"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">4</span>
                    <span class="step-text">Shipping Payment</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_5_tab" data-step="5"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">5</span>
                    <span class="step-text">Approve Payment</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_6_tab" data-step="6"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">6</span>
                    <span class="step-text">Tracking</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_7_tab" data-step="7"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">7</span>
                    <span class="step-text">On the way to Customer</span>
                </div>
            </a>
        </li>
    </ul>
    
    <!-- START - STEP 1: INITIAL PAYMENT -->
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden" id="step_1_content">
        <div class="step_header_wrap block justify-content-around w-full mb-3">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Initial Payments</h1>
            <h1 class="text-xl text-gray-900 dark:text-white mb-2">  
                Request Type: 
                <span class="font-bold text-yellow-300">
                    {{ strpos(strtolower($request_method), 'request') !== false || strpos(strtolower($request_method), 'declared') !== false ? 'Request Estimate' : 'Declared Estimate' }}
                </span>
            </h1>
        </div>  
 
                @include('tickets.timeline-steps.step-1-initial-payment', 
                        ['ticket_id' => $ticket_id,
                        'customer_id' => $customer_id,
                        'customer_fname' => $existing_ticket->Customer->first_name,
                        'firstTicket' => $firstTicket,
                        'steps' => $existing_ticket->steps,
                        'additonal_fees' => $existing_ticket->ticketAdditionalFees,
                        'existing_ticket' => $existing_ticket,
                        'products' => $products,
                        'status' => $existing_ticket->status,
                        'request_method' => $request_method, 
                        'admin_settings' => $admin_settings,
                        'ticketPayments' => $existing_ticket->ticketPayments ])

        
    </div>
    <!-- END - STEP 1: INITIAL PAYMENT -->

    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden" id="step_2_content">
        <div class="step_header_wrap block justify-content-around w-full mb-3">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Approve Payment</h1>
            <h1 class="text-xl text-gray-900 dark:text-white mb-2">  
                Request Type: 
                <span class="font-bold text-yellow-300">
                    {{ strpos(strtolower($request_method), 'request') !== false || strpos(strtolower($request_method), 'declared') !== false ? 'Request Estimate' : 'Declared Estimate' }}
                </span>
            </h1>
        </div>  

                @include('tickets.timeline-steps.step-2-approve-payment', 
                        ['ticket_id' => $ticket_id,
                        'customer_id' => $customer_id, 
                        'initialPaymentData' => $initialPaymentData ])

        
    </div>
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden" id="step_3_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Add Pictures</h3>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur provident, asperiores libero reiciendis autem consequatur exercitationem consequuntur tenetur architecto officia, sed quod praesentium accusantium iure? Veritatis nemo sunt magnam nihil.</p>
    </div>
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden" id="step_4_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Shipping Payment</h3>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur provident, asperiores libero reiciendis autem consequatur exercitationem consequuntur tenetur architecto officia, sed quod praesentium accusantium iure? Veritatis nemo sunt magnam nihil.</p>
    </div>
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden" id="step_5_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Approve Payment</h3>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur provident, asperiores libero reiciendis autem consequatur exercitationem consequuntur tenetur architecto officia, sed quod praesentium accusantium iure? Veritatis nemo sunt magnam nihil.</p>
    </div>
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden" id="step_6_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tracking</h3>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur provident, asperiores libero reiciendis autem consequatur exercitationem consequuntur tenetur architecto officia, sed quod praesentium accusantium iure? Veritatis nemo sunt magnam nihil.</p>
    </div>
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden" id="step_7_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">On the way to Customer</h3>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aspernatur provident, asperiores libero reiciendis autem consequatur exercitationem consequuntur tenetur architecto officia, sed quod praesentium accusantium iure? Veritatis nemo sunt magnam nihil.</p>
    </div>
</div>



