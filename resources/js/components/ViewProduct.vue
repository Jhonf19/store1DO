<template>
    <div>
        <!-- <img src="/storage/products/p_1585801497.png" alt="imgp"> -->
        <div class="row mt-3">
            <div class="col-md-4">
                <p>APIs</p>
                <h2><span><i class="fa fa-users"></i></span> Productos</h2>
            </div>
            <div class="col-md-4 offset-md-4 shadow card-body">
                  <b-input-group class="">
                    <b-form-input v-model="search" placeholder="Buscar..."></b-form-input>
                    <b-input-group-append>
                    <b-button @click="searchProduct" variant="outline-primary"><i class="fa fa-search"></i></b-button>
                    </b-input-group-append>
                </b-input-group>
            </div>
        </div><br><br>
        <div class="row">
            <div class="col-md-12 shadow card-body">
                    <b-button class="shadow-lg" pill v-b-modal.modal-product  variant="primary">
                        <i class="fa fa-user-plus"></i> Nuevo Producto
                    </b-button>
                    <p class="float-right text-muted mt-5">{{ this.table.products.length }} registros</p>
                    <b-table id="tableProduct" :per-page="perPage" :current-page="currentPage" responsive striped hover :items="table.products" sort-icon-left :busy="isBusy" :fields="table.fields">
                        <template v-slot:table-busy>
                            <div class="text-center text-success my-5">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>Cargando...</strong>
                            </div>
                        </template>
                        <template v-slot:cell(#)="data">
                            {{ data.index + 1 }}
                        </template>
                        <template v-slot:cell(image)="products">
                            <b-img thumbnail width="100" :src="'storage/'+products.item.image" alt="img"></b-img>
                        </template>
                        <template v-slot:cell(acciones)="products">
                            <b-button squared @click="showCustomer(products.item.id)" v-b-tooltip.hover.topleft title="Ver mas" class="btn btn-info">
                                <i class="fa fa-info"></i>
                            </b-button>
                            <b-button squared href="/products/"  v-b-tooltip.hover.topleft.v-success title="Abono" class="btn btn-success">
                                <i class="fa fa-hand-holding-usd"></i>
                            </b-button>
                        </template>
                    </b-table>
                    <b-pagination
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    aria-controls="tableProduct"
                    align="center"
                    first-number
                    last-number
                    ></b-pagination>
            </div>
        </div>
        <b-modal id="modal-product" ref="modalProduct" title="Nuevo Producto" size="lg">
            <b-form @submit.stop.prevent enctype="multipart/form-data">
                <b-input-group class="form-group">
                    <b-form-input
                    id="input-1"
                    v-model="form.description"
                    type="text"
                    required
                    placeholder="Descripción"
                    ></b-form-input>
                </b-input-group>
                <b-input-group class="form-group">
                    <b-form-input
                    id="input-2"
                    v-model="form.cost"
                    type="number"
                    required
                    placeholder="Costo"
                    ></b-form-input>
                </b-input-group>
                <b-input-group class="form-group">
                    <b-form-input
                    id="input-3"
                    v-model="form.price"
                    type="number"
                    required
                    placeholder="Precio"
                    ></b-form-input>
                </b-input-group>
                <b-input-group class="form-group">
                    <b-form-input
                    id="input-4"
                    v-model="form.stock"
                    type="number"
                    required
                    placeholder="Existencia"
                    ></b-form-input>
                </b-input-group>
                <b-form-file v-model="form.image" browse-text="Imagen" name="images" capture accept="image/*" class="form-control" plain></b-form-file>
                <!-- <b-button type="submit" variant="primary">Submit</b-button>
                <b-button type="subit" variant="danger">Reset</b-button> -->
            </b-form>
                <template v-slot:modal-footer="{ cancel, ok }">
                    <b-button size="sm" variant="danger" @click="cancel()">
                        Cancelar
                    </b-button>
                    <b-button @click="newProduct()" size="sm" variant="primary">
                        Crear
                    </b-button>
                    
                </template>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                form:{
                    description:'',
                    cost:'',
                    price:'',
                    stock:'',
                    image: null
                },
                table:{
                    products:[],
                    fields:[
                        '#',
                        {
                            key:'description',
                            label: 'Descripción',
                            sortable: true
                        },
                        {
                            key:'cost',
                            label: 'Costo',
                            sortable: true
                        },
                        {
                            key:'price',
                            label: 'Precio',
                            sortable: true
                        },
                        {
                            key: 'stock',
                            label: 'Existencia',
                            sortable: true
                        },
                        {
                            key: 'image',
                            label: 'Imagen'
                        },
                        'Acciones'
                    ]
                },
                isBusy: false,
                perPage: 3,
                currentPage: 1,
                search:''
            }

        },
       components:{
       },
        computed: {
      rows() {
        return this.table.products.length
      }
    },
       mounted(){
           this.getProducts()
              
           
       },
       methods:{
           getProducts(){
               this.isBusy = true
                axios.get('/getProducts')
               .then( res => {
                //    console.log(res);
                   this.table.products = res.data
                   this.isBusy = false
               })
               .catch( err => {
                   console.error(err);
               });
           },
           newProduct(){
            //    alert('cus')
            // console.log(this.form.image);
            let formData = new FormData();
            formData.append( 'description', this.form.description)
            formData.append( 'cost', this.form.cost)
            formData.append( 'price', this.form.price)
            formData.append( 'stock', this.form.stock)
            formData.append( 'image', this.form.image)
            
               axios.post('/products', formData)
                .then(function (res) {
                    // console.log(res);
                    // this.alert = res.data
                })
                .catch(function (error) {
                    console.log(error);
                });
                var msg = 'Producto agregado'
                var color = 'success'
                var title = '¡Bien Hecho!'
                this.alertOk(msg, color, title)
                this.hideModal()
                this.form = {}
                this.getProducts()
           },
            hideModal() {
                this.$refs['modalProduct'].hide()
            },
            alertOk(msg,color,title) {
                // this.boxTwo = ''
                this.$bvModal.msgBoxOk(msg, {
                title: title,
                size: 'sm',
                buttonSize: 'sm',
                okVariant: color,
                headerClass: 'p-2 border-bottom-0',
                footerClass: 'p-2 border-top-0',
                centered: true
                })
                .then(value => {
                    // this.boxTwo = value
                })
                .catch(err => {
                    // An error occurred
                })
            },
            searchProduct(id){
               this.isBusy = true
                axios.get('/searchProducts?word='+this.search)
               .then( res => {
                //    console.log(res);
                   this.table.products = res.data
                   this.isBusy = false
                   this.search = ''
               })
               .catch( err => {
                   console.error(err);
               });
            },
            showCustomer(id){
                let pa = window.location
                window.location = pa+'/'+id;
                
                // window.location.reload("/products/"+id)
            }
       }
    }
</script>

<style lang="css" scoped>

</style>