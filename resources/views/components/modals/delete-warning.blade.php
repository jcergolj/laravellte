<div class="modal show modal-backdrop" tabindex="-1" role="dialog" x-bind:class="{ 'd-block': showModal }">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" x-on:click.prevent="showModal = false">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" x-on:click.prevent="showModal = false">Close</button>
                <button type="button" class="btn btn-light" x-on:click.prevent="$wire.destroy(deleteId); showModal = false">Delete</button>
            </div>
        </div>
    </div>
</div>
