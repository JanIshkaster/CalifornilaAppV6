<fieldset id="step-3" role="tabpanel" aria-labelledby="steps" class="body" aria-hidden="true"> 
  
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mt-2 mb-4">Drag and Drop media to upload</h1>
                
                <form action="{{ route('uploadFiles', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}" 
                    method="post" enctype="multipart/form-data" id="image-upload" class="dropzone p-0 rounded-lg">
                    @csrf   
                    <div class="m-0 dz-message flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                        </div> 
                    </div>
                </form>
            </div>
            <div class="col-md-12 mt-4"> 
                @if ($ticketMedia && $ticketMedia->first()) 
                    <h1 class="mt-2 mb-3 bold text-xl font-bold text-gray-900 dark:text-white mb-2">Uploaded Media:</h1> 
                    <div class="row "> 
                        @forelse ($ticketMedia as $image)
                            <div class="img-container w-1/6 h-full bg-white border rounded-lg p-0 mx-2 my-3 relative">
                                <a href="{{ asset('storage/' . $image->image_path) }}" data-lightbox="uploaded-images">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Uploaded Image" style="width:100%;height:220px;object-fit:cover;">  
                                </a>
                                <div class="delete-icon absolute top-0 right-0">
                                    <form id="delete-form-{{ $image->id }}" action="{{ route('deleteFiles', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id, 'media_id' => $image->id]) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button class="btn p-0 m-0 text-xl z-50 text-red-600 hover:text-gray-900" onclick="confirmDeleteMedia(event, '{{ $image->id }}')">
                                        <span class="mdi mdi-close-box"></span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p>No images uploaded yet.</p>
                        @endforelse 
                    </div>
                    
                        <form method="POST" id="comment_submit" action="{{ route('mediaComment', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}">
                            @csrf 
                            <input type="hidden" name="customer_first_name" value="{{ $existing_ticket->Customer->first_name }}" /> 
                            <input type="hidden" name="email_type" value="emailMediaComment" />
                            <input type="hidden" value="{{ $ticket_id }}" name="ticket_id" class="ticket_id"> 
                            <input type="hidden" value="{{ $customer_id }}" name="customer_id" class="customer_id"> 
                            <input type="hidden" value="{{ $customer_fname }}" name="customer_first_name" class="customer_first_name">
                            
                            @foreach ($ticketMedia as $image)
                                <input type="hidden" name="uploaded_images[]" value="{{ $image->image_path }}">
                            @endforeach

                            <div class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                                <div class="p-0 bg-white rounded-t-lg dark:bg-gray-800">
                                    <label for="mediaComment" class="sr-only">Your comment</label>
                                    <textarea name="mediaComment" id="mediaComment" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                    placeholder="Write a comment..." required ></textarea>
                                </div>
                                <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600">
                                    <button type="submit" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800">
                                        Send comment to Customer
                                    </button> 
                                </div>
                                
                                <h1 class="px-3 py-2 text-xl font-bold mt-4">Comment(s) sent:</h1>
                                @forelse ($mediaComments as $mediaComment)
                                    <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600 mt-3 mb-3">
                                        <div class="mediaComment_first_col">
                                            <strong>#{{ $loop->iteration }}:</strong> 
                                            {{ $mediaComment->comment }}
                                        </div>
                                        <div class="mediaComment_second_col"> 
                                            {{ date('F d, Y | H:i', strtotime($mediaComment->created_at)) }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex items-center justify-between px-3 py-2 border-t dark:border-gray-600 mt-4 mb-4">
                                        No comment(s) yet
                                    </div>
                                @endforelse



                            </div>
                        </form>
                         
                @endif
            </div>
            <a onclick="return confirmProceedStepFour();" href="{{ route('step_4', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}"  
                class="w-auto mx-2 text-gray bg-yellow-800 hover:bg-yellow-900 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-large rounded-lg text-md px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-yellow-300 dark:text-gray-800 dark:hover:bg-yellow-400 dark:focus:ring-yellow-800">
                <span class="mdi mdi-page-next" style="margin-right:5px;"></span>
                Proceed to next step?
            </a>
                {{-- SWAL FOR PROCEED BUTTON --}}
                <script>
                    function confirmProceedStepFour() {
                        Swal.fire({
                            title: 'Proceed to next step?',
                            text: 'Are you sure you want to proceed?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'Cancel',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // User clicked "Yes," proceed with the link
                                window.location.href = '{{ route('step_4', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}';
                            }
                        });
                
                        // Prevent the default link behavior
                        return false;
                    }
                </script>
        </div>
    </div>


</fieldset>
 
{{-- SWAL CONFIRM FOR DELETE MEDIA --}} 
<script>
    function confirmDeleteMedia(event, imageId) {
        event.preventDefault(); 
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete image!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + imageId).submit();
            }
        })
    }
 
</script>

{{-- DRAG AND DROP UPLOAD IMAGES --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css" rel="stylesheet"> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"></script>

<script>
    Dropzone.options.imageUpload = {
        paramName: 'file', // The name that will be used to transfer the file
        maxFilesize: 20, // MB
        acceptedFiles: 'image/*',
        dictDefaultMessage: '', // Hide the default Dropzone message
        init: function() {
            const dropzoneInstance = this;
            dropzoneInstance.on("addedfile", function() {
                document.querySelector("#image-upload .dz-message").style.display = 'none';
            });
            dropzoneInstance.on("removedfile", function() {
                if (dropzoneInstance.files.length === 0) {
                    document.querySelector("#image-upload .dz-message").style.display = 'flex';
                }
            });
        }
    };
</script>
{{-- END - DRAG AND DROP UPLOAD IMAGES --}}


