<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/eventos/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Evento
    </a>
</div>


<div class="dashboard__contenedor">
    <?php if(!empty($eventos)) { ?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Evento</th>
                    <th scope="col" class="table__th">Categoría</th>
                    <th scope="col" class="table__th">Día y Hora</th>
                    <th scope="col" class="table__th">Ponente</th>
                    <th scope="col" class="table__th"></th>
                </tr>
            </thead>

            <tbody class="table__tbody">
                <?php foreach($eventos as $evento) { ?>
                    <tr class="table__tr">
                        <td class="table__td">
                            <?php echo $evento->nombre; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $evento->categoria->nombre; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $evento->dia->nombre . ", " . $evento->hora->hora; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $evento->ponente->nombre . " " . $evento->ponente->apellido; ?>
                        </td>
                        <td class="table__td--acciones">
                            <a class="table__accion table__accion--editar" href="/admin/eventos/editar?id=<?php echo $evento->id; ?>">
                                <i class="fa-solid fa-pencil"></i>
                                Editar
                            </a>

                            <form method="POST" action="/admin/eventos/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $evento->id; ?>">
                                <button class="table__accion table__accion--eliminar" type="submit">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No Hay Eventos Aún</p>
    <?php } ?>
</div>

<?php 
    echo $paginacion;
?>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const tbody = document.querySelector(".table__tbody");
    const apiURL = "http://localhost:8080/api/eventosAPI.php";

    try {
        const response = await fetch(apiURL);
        const eventos = await response.json();

        if (!Array.isArray(eventos) || eventos.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center">No hay eventos aún</td></tr>`;
            return;
        }

        tbody.innerHTML = "";

        eventos.forEach(evento => {
            const row = document.createElement("tr");
            row.classList.add("table__tr");

            row.innerHTML = `
                <td class="table__td">${evento.nombre}</td>
                <td class="table__td">${evento.categoria}</td>
                <td class="table__td">${evento.dia}, ${evento.hora}</td>
                <td class="table__td">${evento.ponente}</td>
                <td class="table__td--acciones">
                    <a class="table__accion table__accion--editar" href="/admin/eventos/editar?id=${evento.id}">
                        <i class="fa-solid fa-pencil"></i> Editar
                    </a>
                    <form method="POST" action="/admin/eventos/eliminar" class="table__formulario">
                        <input type="hidden" name="id" value="${evento.id}">
                        <button class="table__accion table__accion--eliminar" type="submit">
                            <i class="fa-solid fa-circle-xmark"></i> Eliminar
                        </button>
                    </form>
                </td>
            `;

            tbody.appendChild(row);
        });
    } catch (error) {
        console.error("Error al cargar eventos:", error);
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-red">Error: ${error.message}</td></tr>`;
    }
});
</script>
<?php 
    echo $paginacion;
?>
