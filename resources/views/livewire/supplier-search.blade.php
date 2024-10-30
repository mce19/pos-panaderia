<div>
    <div>
        <div class="input-group" wire:ignore>
            <input class="form-control" type="text" id="inputSupplier" placeholder="F1">
            <span class="input-group-text list-light">
                <i class="search-icon" data-feather="user"></i>
            </span>
        </div>

        <script>
            //buscar cualquier rut en sistema
  if (document.querySelector('#inputSupplier')) {
                    new TomSelect('#inputSupplier', {
                        maxItems: 1,
                        valueField: 'id',
                        labelField: 'name',
                        searchField: ['name', 'address'],
                        load: function(query, callback) {
                            var url = "{{ route('data.suppliers') }}" + '?q=' + encodeURIComponent(
                                query)
                            fetch(url)
                                .then(response => response.json())
                                .then(json => {                                    
                                    callback(json);
                                    //console.log(json);
                                }).catch(() => {
                                    callback();
                                });
                        },
                        onChange: function(value) {                                                         
                            var supplier = this.options[value] 
                            Livewire.dispatch('purchase_supplier', {supplier: supplier})

                        },
                        render: {
                            option: function(item, escape) {
                                return `<div class="py-1 d-flex">
            <div>
                <div class="mb-0">
                    <span class="h5 text-info">
                        <b class="text-dark">${ escape(item.id) } 
                    </span>                    
                    <span class="text-warning">|${ escape(item.name.toUpperCase()) }</span>                   
                </div>
            </div>
        </div>`;
                            },
                        },
                    });
    }
  
        </script>
    </div>
</div>