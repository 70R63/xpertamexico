<div class="table-responsive">
	<table id="exportGeneral" class="table table-striped table-bordered text-nowrap" >
		<thead>
			<tr>
				<th>ID</th>
				<th>EMPRESA</th>
				<th>CONTACTO</th>
				<th>EMAIL</th>
				<th>REGISTRADO</th>
				<th>MODIFICADO</th>
				<th>ACCIONES</th>
			</tr>
		</thead>
		<tbody>

			@foreach( $tabla  as $objeto)
				<tr>
					<td>{{ $objeto->id }}</td>
					<td>{{ $objeto->nombre }}</td>
					<td>{{ $objeto->contacto }}</td>
					<td>{{ $objeto->email }}</td>
					<td>{{ $objeto->created_at }}</td>
					<td>{{ $objeto->updated_at }}</td>
					<td>
						<a href=" {{ route('empresas.edit', $objeto->id) }} " class="text-info tx-20 ">
							<i class="fe fe-edit" alt="Editar"></i>
						</a>
						<a href="" class="remove-list text-danger tx-20 remove-button" data-toggle="modal" data-target="#modal{{ $objeto->id }}" >
							<i class="fa fa-trash" alt="Eliminar"></i>
						</a>

						<a href="" class="remove-list text-nowrap tx-20" data-toggle="modal" data-target="#asigarLtd{{ $objeto->id }}" >
							<i title="Asignar LTD" class="fa fa-handshake-o"> </i>
						</a>

						@include('empresas.modals.eliminar')
						@include('empresas.modals.asignar_ltd')		
					</td>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
		    <tr>
		      <td colspan="6">Los datos son responsabilidad del cliente</td>
		    </tr>
		</tfoot>
	</table>
</div>