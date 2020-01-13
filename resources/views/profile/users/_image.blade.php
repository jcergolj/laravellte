<li class="list-group-item" x-data="{ show: false }" x-cloak>
    <div class="row" x-show="!show" x-transition:enter="fade">
        <div class="col-3">
            <b>Image</b>
        </div>
        <div class="col-6 text-center">
            <img
                class="profile-user-img img-fluid img-circle"
                src="{{ $user->image_file }}"
            />
        </div>
        <div class="col-3">
            <a href="#" class="float-right" x-on:click="show = true" data-turbolinks="false">Change</a>
        </div>
    </div>
    <div x-show="show" x-transition:enter="fade">
        <div class="input-group mb-3">
            <div class="custom-file">
                <input
                    type="file"
                    id="user-image"
                    name="image"
                    class="custom-file-input @errorClass('image')"
                    x-data
                    x-on:change="file.upload('/profile/image', $event.target)"
                    required
                >
                <label class="custom-file-label" for="user-image">Choose file</label>
            </div>

            <x-inputs.error id="image" />
        </div>

        <div class="row">
            <div class="offset-8 col-4">
                <button type="button" class="btn btn-outline-secondary btn-block" x-on:click="show = false">Cancel</button>
            </div>
        </div>
    </div>
</li>

@push('scripts')
<script>
    var file = {
        upload : function (url, file) {
            let formData = new FormData();
            formData.append('image', file.files[0]);
            axios.post('/profile/image',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(response => {
                if (response.status === 204) {
                    window.location.reload(false); 
                }
            }).catch(function (error) {
                if (error.response.status !== 422) {
                    return;
                }
                
                document.getElementById('image').innerHTML = error.response.data.errors.image[0];
                document.getElementById('image').classList.remove('d-none');
            });
        }
    }
</script>    
@endpush