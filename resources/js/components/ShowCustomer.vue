<template>
    <div>
        <div class="card">
            <div class="card-header">Información Personal
                <b-button @click="openModal" v-b-tooltip.hover.left title="Editar información" class="float-right" variant="warning">
                <!-- <b-button v-b-modal.modal-customer v-b-tooltip.hover.left title="Editar información" class="float-right" variant="warning"> -->
                    <i class="fa fa-edit"></i>
                </b-button>
            </div>
            <div class="card-body">
                <h2>{{ this.customer.name }} {{ this.customer.last_name }}</h2>
                <h3>{{ this.customer.document }}</h3>
                <h4>{{ this.customer.created_at }}</h4>
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
            <div class="card-header">Eliminar Cliente</div>
            <div class="card-body">
                <b-button @click="alertConfirm" variant="danger" v-b-tooltip.hover.top title="Eliminar cliente"><i class="fa fa-trash-alt"></i></b-button>
            </div>
        </div>
        <!-- Modal edit -->
        <b-modal id="modal-customer" ref="modalCustomer" title="Editar Información" size="lg">
            <b-form @submit.stop.prevent>
                <b-form-group label="Documento" label-for="input-1">
                    <b-form-input
                    id="input-1"
                    v-model="form.document"
                    type="text"
                    required
                    placeholder="Numero de documento"
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Nombres" label-for="input-2">
                    <b-form-input
                    id="input-2"
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="Nombres"
                    ></b-form-input>
                </b-form-group>
                <b-form-group label="Apellidos" label-for="input-3">
                    <b-form-input
                    id="input-3"
                    v-model="form.last_name"
                    type="text"
                    required
                    placeholder="Apellidos"
                    ></b-form-input>
                </b-form-group>
                <!-- <b-button type="submit" variant="primary">Submit</b-button>
                <b-button type="subit" variant="danger">Reset</b-button> -->
            </b-form>
                <template v-slot:modal-footer="{ cancel, editCustomer }">
                    <b-button size="sm" variant="danger" @click="cancel()">
                        Cancelar
                    </b-button>
                    <b-button @click="editCustomer()" size="sm" variant="primary">
                        Guardar Cambios
                    </b-button>
                    
                </template>
        </b-modal>
        <!-- Modal edit -->
    </div>
</template>

<script>
    export default {
        data(){
            return {
                customer:{},
                credit:{},
                  form:{
                    document:'',
                    name:'',
                    last_name:''
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
                axios.get('/getCustomerInfo/'+id_customer)
                .then( res => {
                    // console.log(res);
                    this.customer = res.data.customer
                    this.credit = res.data.credit
                
                })
                .catch( err => {
                    console.error(err);
                })
            },
            editCustomer(){
                axios.put('/customers/'+id_customer,{
                    document: this.form.document,
                    name: this.form.name,
                    last_name: this.form.last_name
                })
                .then( res => {
                    // console.log(res);
                     var msg = 'Información Editada'
                    var color = 'success'
                    var title = '¡Bien Hecho!'
                    this.alertOk(msg, color, title)
                    this.hideModal()
                    
                })
                .catch( err => {
                    console.error(err);
                    
                });
                this.hideModal()
                this.getCustomer()
            },
            deleteCustomer(){
                // window.location = "/customers/"+id;
                axios.delete('/customers/'+id_customer)
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
                this.form.document = this.customer.document
                this.form.name = this.customer.name
                this.form.last_name = this.customer.last_name
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
                this.$bvModal.msgBoxConfirm('Se eliminará a '+this.customer.name+' '+this.customer.last_name, {
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
                        this.deleteCustomer()
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