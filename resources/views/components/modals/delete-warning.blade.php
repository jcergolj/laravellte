<div class="modal show modal-backdrop" tabindex="-1" role="dialog" x-ref="modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="$refs.modal.classList.remove('d-block')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="$refs.modal.classList.remove('d-block')">Close</button>
                <button type="button" class="btn btn-light" @click="window.livewire.emit('destroy', deleteId)">Delete</button>
            </div>
        </div>
    </div>
</div>