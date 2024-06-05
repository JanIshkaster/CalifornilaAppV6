<fieldset id="step-3" role="tabpanel" aria-labelledby="steps" class="body" aria-hidden="true"> 
  
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mt-2 mb-4">Drag and Drop media to upload</h1>
      
                <form action="{{ route('uploadFiles', ['customer_id' => $customer_id, 'ticket_id' => $ticket_id]) }}" 
                    method="post" enctype="multipart/form-data" id="image-upload" class="dropzone rounded-lg bg-white-600">
                    @csrf 
                </form>

            </div>
            <div class="col-md-12 mt-4"> 
                @if ($ticketMedia && $ticketMedia->first()) 
                    <h1 class="mt-2 mb-4">Uploaded Media:</h1> 
                    <div class="row"> 
                        @forelse ($ticketMedia as $image)
                            <div class="img-container w-1/6 h-full bg-white border rounded-lg p-0 mx-2 my-3">
                                <a href="{{ asset('storage/' . $image->image_path) }}" data-lightbox="uploaded-images">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Uploaded Image" style="width:100%;height:220px;object-fit:cover;"> 
                                </a>
                            </div> 
                        @empty
                            <p>No images uploaded yet.</p>
                        @endforelse
                    </div>
                @endif
            </div>
            
        </div>
    </div>


</fieldset>
 
