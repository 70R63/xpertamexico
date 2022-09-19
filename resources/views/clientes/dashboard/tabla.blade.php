<div class="table-responsive">
	<table id="exportGeneral" class="table table-striped table-bordered text-nowrap" >
		<thead>
			<tr>
				<th>ID</th>
				<th>NOMBRE</th>
				<th>CONTACTO</th>
				<th>DIRECCION</th>
				<th>COLONIA</th>
				<th>CIUDAD</th>
				<th>CP</th>
				<th>ENTIDAD FEDERATIVA</th>
				<th>ACCIONES</th>
			</tr>
		</thead>
		<tbody>

			@foreach( $tabla  as $objeto)
				<tr>
					<td>{{ $objeto->id }}</td>
					<td>{{ $objeto->nombre }}</td>
					<td>{{ $objeto->contacto }}</td>
					<td>{{ $objeto->direccion }}</td>
					<td>{{ $objeto->colonia }}</td>
					<td>{{ $objeto->ciudad }}</td>
					<td>{{ $objeto->cp }}</td>
					<td>{{ $objeto->entidad_federativa }}</td>
					<td>
						<a href=" {{ route('clientes.edit', $objeto->id) }} " class="text-info tx-20 ">
							<i class="fe fe-edit" alt="Editar"></i>
						</a>
						<a href="" class="remove-list text-danger tx-20 remove-button" data-toggle="modal" data-target="#modal{{ $objeto->id }}" >
	<i class="fa fa-trash" alt="Eliminar"></i>
</a>
						@include('clientes.modals.eliminar')		
					</td>
				</tr>
					
			@endforeach

		</tbody>
		<tfoot>
		    <tr>
		      <td colspan="8">Los datos son responsabilidad del cliente</td>
		    </tr>
		</tfoot>
	</table>
</div>