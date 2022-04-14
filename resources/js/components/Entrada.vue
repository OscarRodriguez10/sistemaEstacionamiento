<template>
	<div>
		<h1 class="text-center"> Registro de Entrada</h1>
		<hr>
		<!-- Button trigger modal -->
			<button @click="update=false; openModal();" type="button" class="btn btn-primary">
			  Nueva Entrada
			</button>
			<a target="_blank" v-bind:href="'/reporteEntrada'"><button class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i>Reporte</button></a>
			<!-- Modal -->
			<div class="modal" :class="{show:modal}">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{titleModal}}</h5>
                <button @click="closeModal();" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
			      <div class="modal-body">
			        <div>
			        	<label for="nombre">Nombre del encargado del Vehiculo</label>
			        	<input v-model="entrada.nombre" type="text" class="form-control" id="nombre" placeholder="Nombre" name="nombre">
			        </div>
			        <div>
			        	<label for="celular">Celular</label>
			        	<input v-model="entrada.celular" type="text" class="form-control" id="celular" placeholder="Celular" name="ceular">
			        </div>

			        <div>
			        	<label for="idVehiculo">Vehiculo</label>
                		<select class="form-control" v-model="entrada.idvehiculo">
                        	<option>Seleccionar Vehiculo</option>
    						<option v-for="vehiculo in vehiculos" v-bind:value="vehiculo.idvehiculo" >{{ vehiculo.descripcion }} {{ vehiculo.numeroPlaca }}</option>
                    	</select>
                 	</div>
                 	
			        <div>
			        	<label for="estado">Estado</label>
                		<select v-model="entrada.estado" class="form-control" id="entrada" name="entrada" >
                        	<option value="Activo">Activo</option>
                        	<option value="Inactivo">Inactivo</option>
                    	</select>
                 	</div>
			      </div>
			      <div class="modal-footer">
			          <button @click="closeModal();" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button @click="save();" type="button" class="btn btn-success">Guardar Cambios</button>
			      </div>
			    </div>
			  </div>
			</div>
			<table class="table table-striped table-hover">
  				<thead class="table-dark">
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">Nombre</th>
			      <th scope="col">Celular</th>
			      <th scope="col">Vehiculo</th>
			      <th scope="col">Fecha </th>
			      
			      <th scope="col">Estado</th>
			      <th scope="col" colspan="2" class="text-center">Accion</th>
			    </tr>
			  </thead>
			  <tbody>
			    <tr v-for="entrada in entradas" :key="entrada.idEntrada">
			      <th scope="row">{{ entrada.idEntrada}}</th>
			      <td>{{ entrada.nombre}}</td>
			      <td>{{ entrada.celular}}</td>
			      <td>{{ entrada.vehiculo.descripcion}}</td>
			      <td>{{ entrada.fecha_hora}}</td>
			      <td>{{ entrada.estado}}</td>

			      <td>
			      	<button @click="update=true; openModal(entrada);" class="btn btn-warning">Editar</button>
			      </td>
			      <td>
			      	<button @click="eliminar(entrada.idEntrada)" class="btn btn-danger">Eliminar</button>
			      </td>
			    </tr>
			  </tbody>
			</table>
	</div>
</template>

<script>
	export default{
		data () {
			return {
				entrada: {
					nombre:'',
					celular: '',
					idvehiculo:'',
					idusuario:'',
					estado:''
				},
				id:0,
				update:true,
				modal:0,
				titleModal:'',
				entradas:[],

				vehiculo: {
					idvehiculo:'',
					descripcion:'',
					numeroPlaca:'',
					estado:''
				},

				vehiculos:[],
				
			}
		},
		mounted: function () {
        	const {data} = this;
         	this.listaVehiculo();
       	},
		methods: {
			async list(){
				const res = await axios.get('entradas');
				this.entradas = res.data;
			},
			async listaVehiculo(){
				const res = await axios.get('vehiculos');
				this.vehiculos = res.data;
			},
			async eliminar(idEntrada){
				const res = await axios.delete('/entradas/' + idEntrada);
				this.list();
			},
			async save() {

                if(this.update)
                {
                    const res = await axios.put('/entradas/' + this.id, this.entrada);
                }else{
                    const res = await axios.post('/entradas', this.entrada);
                }
                this.closeModal();
                this.list();
            },
			openModal(data={}){
				this.modal=1;
				if(this.update){
					this.id = data.idEntrada;
					this.titleModal="Modificar Entrada";
					this.entrada.nombre = data.nombre;
					this.entrada.celular = data.celular;
					this.entrada.estado = data.estado;
				}else{
					this.id=0;
					this.titleModal ="Registrar Entrada";
					this.entrada.nombre ='';
					this.entrada.celular = '';
					this.entrada.estado = '';
				} 

			},
			closeModal(){
				this.modal=0;
			},
		},

		created(){
			this.list();
		},
	}
</script>
<style>
	.show {
	display: list-item;
	opacity: 1;
	background: rgba(44, 38, 75, 0.849);
	}
</style>