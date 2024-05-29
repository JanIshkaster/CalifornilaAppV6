<div class="md:flex w-full mt-5" id="timeline">
    <ul class="flex-column space-y space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0">
        <li>
            <a href="#" id="step_1_tab"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white active"
                aria-current="page">
                <div class="title"><span class="step-number">1</span>
                    <span class="step-text">Initial Payment</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_2_tab"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">2</span>
                    <span class="step-text">Approve Payment</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_3_tab"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">3</span>
                    <span class="step-text">Add Pictures</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_4_tab"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">4</span>
                    <span class="step-text">Shipping Payment</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_5_tab"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">5</span>
                    <span class="step-text">Approve Payment</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_6_tab"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">6</span>
                    <span class="step-text">Tracking</span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" id="step_7_tab"
                class="tab-link inline-flex items-center px-4 py-3 rounded-lg hover:text-gray-900 bg-gray-50 hover:bg-gray-100 w-full dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <div class="title"><span class="step-number">7</span>
                    <span class="step-text">On the way to Customer</span>
                </div>
            </a>
        </li>
    </ul>
    
    <!-- START - STEP 1: INITIAL PAYMENT -->
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full"
        id="step_1_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Initial Payments</h3>
 
                @include('tickets.timeline-steps.step-1-initial-payment', 
                ['ticket_id' => $ticket_id,
                'firstTicket' => $firstTicket,
                'notes' => $existing_ticket->ticketNotes,
                'steps' => $existing_ticket->steps,
                'additonal_fees' => $existing_ticket->ticketAdditionalFees,
                'existing_ticket' => $existing_ticket,
                'products' => $products,
                'request_method' => $request_method ])

        <p class="mb-2">This is some placeholder content for the Initial Payment tab's associated content. Clicking another
            tab will toggle the visibility of this one for the next.</p>
        <p>The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
    <!-- END - STEP 1: INITIAL PAYMENT -->

    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden"
        id="step_2_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Approve Payment</h3>
        <p>This is some placeholder content for the Approve Payment tab.</p>
    </div>
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden"
        id="step_3_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Add Pictures</h3>
        <p>This is some placeholder content for the Add Pictures tab.</p>
    </div>
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden"
        id="step_4_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Shipping Payment</h3>
        <p>This is some placeholder content for the Shipping Payment tab.</p>
    </div>
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden"
        id="step_5_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Approve Payment</h3>
        <p>This is some placeholder content for the Approve Payment tab.</p>
    </div>
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden"
        id="step_6_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tracking</h3>
        <p>This is some placeholder content for the Tracking tab.</p>
    </div>
    <div class="tab-content p-6 bg-gray-50 text-medium text-gray-500 dark:text-gray-400 dark:bg-gray-800 rounded-lg w-full hidden"
        id="step_7_content">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">On the way to Customer</h3>
        <p>This is some placeholder content for the On the way to Customer tab.</p>
    </div>
</div>



