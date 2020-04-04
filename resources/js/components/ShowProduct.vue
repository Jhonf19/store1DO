<template>
    <div>
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <b-card :img-src="'../storage/'+this.product.image" img-alt="Image" img-top>
                    <b-button @click="openModal" v-b-tooltip.hover.left title="Editar información" class="float-right" variant="warning">
                        <i class="fa fa-edit"></i>
                    </b-button>
                    <b-button v-b-modal.modalEditFoto style="position: absolute; top:20px;"  v-b-tooltip.hover.right title="Cambiar foto" size="sm" class="float-right" variant="secondary">
                        <i class="fa fa-image"></i>
                    </b-button>
                    <b-button  style="position: absolute; top:60px;"  v-b-tooltip.hover.right title="Ampliar foto" size="sm" class="float-right" variant="secondary">
                        <i class="fa fa-expand"></i>
                    </b-button>
                    <b-card-text>
                        <ul class="list-unstyled">
                            <li>
                                <h3>{{ this.product.description }}</h3>
                            </li>
                            <li><b>Costo: </b>
                                {{ this.product.cost }}
                            </li>
                            <li><b>Precio: </b>
                                {{ this.product.price }}
                            </li>
                            <li><b>Unidades: </b>
                                {{ this.product.stock }}
                            </li>
                        </ul>
                    </b-card-text>
                </b-card>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Creditos</div>
            <div class="card-body">
                <b-button v-if="!this.credit" @click="alertConfirm" variant="primary" v-b-tooltip.hover.top title="Añadir Credito"><i class="fa fa-plus"></i></b-button>
                <p v-else>Listar creditos</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Eliminar Producto</div>
            <div class="card-body">
                <b-button @click="alertConfirm" variant="danger" v-b-tooltip.hover.top title="Eliminar producto"><i class="fa fa-trash-alt"></i></b-button>
            </div>
        </div>
        <!-- Modal edit -->
        <b-modal id="modal-product" ref="modalCustomer" title="Editar Información" size="lg">
            <b-form @submit.stop.prevent>
                <b-form-group label="Descripción" label-for="input-1">
                    <b-form-input
                    id="input-1"
                    v-model="form.description"
                    type="text"
                    required
                    placeholder="Numero de documento"
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Costo" label-for="input-2">
                    <b-form-input
                    id="input-2"
                    v-model="form.cost"
                    type="text"
                    required
                    placeholder="Costo"
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Precio" label-for="input-3">
                    <b-form-input
                    id="input-3"
                    v-model="form.price"
                    type="text"
                    required
                    placeholder="Precio"
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Stock" label-for="input-3">
                    <b-form-input
                    id="input-3"
                    v-model="form.stock"
                    type="text"
                    required
                    placeholder="Stock"
                    ></b-form-input>
                </b-form-group>
                <!-- <b-button type="submit" variant="primary">Submit</b-button>
                <b-button type="subit" variant="danger">Reset</b-button> -->
            </b-form>
                <template v-slot:modal-footer="{ cancel, ok }">
                    <b-button size="sm" variant="danger" @click="cancel()">
                        Cancelar
                    </b-button>
                    <b-button @click="editProduct()" size="sm" variant="primary">
                        Guardar Cambios
                    </b-button>
                    
                </template>
        </b-modal>
        <!-- Modal edit -->
        <!-- Modal zoom-image -->
        <b-modal hide-footer hide-header id="modalFoto2" ref="modalFoto2" centered size="xl">
            <div>

            <b-img  width="100%" :src="'../storage/'+this.product.image" alt="foto-modal"></b-img>
            </div>
        </b-modal>
        <!-- Modal zoom-image -->
        <!-- Modal edit-image -->
        <b-modal id="modalEditFoto" ref="modalEditFoto" title="Cambiar imagen principal" centered>
            <b-form @submit.stop.prevent enctype="multipart/form-data">
                <b-form-file v-model="form.image" browse-text="Imagen" name="image"  accept="image/*" class="form-control" plain></b-form-file>
            <!-- <b-img  width="100%" :src="'../storage/'+this.product.image" alt="foto-modal"></b-img> -->
            </b-form>
             <template v-slot:modal-footer="{ cancel, ok }">
                    <b-button size="sm" variant="danger" @click="cancel()">
                        Cancelar
                    </b-button>
                    <b-button @click="changeImg()" size="sm" variant="primary">
                        Guardar Cambios
                    </b-button>
                </template>
        </b-modal>
        <!-- Modal edit-image -->
    </div>
</template>

<script>
    export default {
        data(){
            return {
                product:{},
                credit:{},
                  form:{
                    description:'',
                    cost:'',
                    price:'',
                    stock:'',
                    image:null
                }

            }
        },
        mounted(){
            this.getCustomer()
            
        },
        computed:{

        },
        methods:{
            getCustomer(){
                axios.get('/getProductInfo/'+id_product)
                .then( res => {
                    // console.log(res);
                    this.product = res.data
                    // this.credit = res.data.credit
                
                })
                .catch( err => {
                    console.error(err);
                })
            },
            editProduct(){
                axios.put('/products/'+id_product,{
                    description: this.form.description,
                    cost: this.form.cost,
                    price: this.form.price,
                    stock: this.form.stock
                })
                .then( res => {
                    // console.log(res);
                     var msg = 'Información Editada'
                    var color = 'success'
                    var title = '¡Bien Hecho!'
                    this.alertOk(msg, color, title)
                    
                })
                .catch( err => {
                    console.error(err);
                    
                });
                this.hideModal()
                this.getCustomer()
            },
            changeImg(){
                let formData = new FormData()
                formData.append( 'image', this.form.image)
                formData.append( 'old_image', this.product.image)
                // alert(formData)
                axios.post('/imgproducts/'+id_product,formData)
                .then( res => {
                    // console.log(res);
                    this.product.image = res.data;
                    // this.form = {}
                    this.$refs['modalEditFoto'].hide()
                     var msg = 'Foto cambiada'
                    var color = 'success'
                    var title = '¡Bien Hecho!'
                    this.alertOk(msg, color, title)
                    this.getCustomer()
                    
                })
                .catch( err => {
                    console.error(err);
                    
                });
            },
            deleteProduct(){
                // window.location = "/customers/"+id;
                axios.delete('/products/'+id_product)
                .then( res => {
                    console.log(res);
                    var msg = 'Cliente Eliminado'
                    var color = 'success'
                    var title = '¡Bien Hecho!'
                    this.alertOk(msg, color, title)
                    window.location = "/customers";
                })
                .catch( err => {
                    console.error(err);
                });
            },
            hideModal() {
                this.$refs['modalCustomer'].hide()
            },
            openModal() {
                this.form.description = this.product.description
                this.form.cost = this.product.cost
                this.form.price = this.product.price
                this.form.stock = this.product.stock
                // this.form.image = this.product.image
                this.$refs['modalCustomer'].show()
            },
             hideModal() {
                this.$refs['modalCustomer'].hide()
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
            alertConfirm(){
                this.$bvModal.msgBoxConfirm('Se eliminará a '+this.product.description, {
                title: '¿Estas seguro?',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'SI',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
                })
                .then(value => {
                    if (value) {
                        this.deleteProduct()
                    } else {
                        
                    }
                    
                })
                .catch(err => {
                    console.error(err);
                    // An error occurred
                })
            }
        }
    }
</script>

<style lang="css" scoped>

</style>