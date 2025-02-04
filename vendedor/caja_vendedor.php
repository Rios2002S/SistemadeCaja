<?php
require_once '../headfooter/head.php'; // Conexión a la base de datos
require_once '../bd/consultas/consultas.php';
?>

<?php
require_once '../add/tablacaja.php';
?>

<!-- Scripts para manejo del carrito -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    let carrito = [];
    const carritoTabla = document.querySelector('#carrito tbody');
    const totalSpan = document.getElementById('total');

    // Evento para agregar productos al carrito
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const nombre = btn.dataset.nombre;
            const precio = parseFloat(btn.dataset.precio);
            const stock = parseInt(btn.dataset.stock);

            // Validar si hay suficiente stock
            let cantidad = prompt(`¿Cuántos ${nombre} deseas agregar? (Stock disponible: ${stock})`, 1);
            cantidad = parseInt(cantidad);

            // Verificar si el usuario canceló el prompt o ingresó un valor no válido
            if (isNaN(cantidad) || cantidad <= 0) {
                alert('No se ha agregado el producto al carrito, ya que la cantidad ingresada no es válida.');
                return; // No hacer nada si el valor es inválido
            }

            if (cantidad > stock) {
                alert(`No hay suficiente stock para agregar ${cantidad} ${nombre}. Solo hay ${stock} en stock.`);
                return;
            }

            // Buscar si el producto ya está en el carrito
            const productoExistente = carrito.find(item => item.id === id);
            if (productoExistente) {
                productoExistente.cantidad += cantidad;
                productoExistente.subtotal += precio * cantidad;
            } else {
                carrito.push({ id, nombre, precio, cantidad, subtotal: precio * cantidad });
            }

            actualizarCarrito();
        });
    });


    // Actualiza la tabla del carrito
    function actualizarCarrito() {
        carritoTabla.innerHTML = '';
        let total = 0;

        carrito.forEach((producto, index) => {
            total += producto.subtotal;

            const row = `
                <tr>
                    <td>${producto.nombre}</td>
                    <td>
                        <input type="number" min="1" class="form-control cantidad" 
                            data-index="${index}" value="${producto.cantidad}">
                    </td>
                    <td>$${producto.subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-danger btn-sm eliminar" data-index="${index}">Eliminar</button>
                    </td>
                </tr>
            `;
            carritoTabla.insertAdjacentHTML('beforeend', row);
        });

        totalSpan.textContent = total.toFixed(2);

        // Mostrar los campos de pago y vuelto si el total es mayor que 0
        if (total > 0) {
            // Mostrar campos de pago y vuelto
            document.getElementById('pagoFields').style.display = 'block';
        } else {
            // Ocultar los campos de pago, vuelto y boton confirmar si el total es 0
            document.getElementById('pagoFields').style.display = 'none';
        }

        // Asignar eventos a los inputs y botones
        document.querySelectorAll('.cantidad').forEach(input => {
            input.addEventListener('input', actualizarCantidad);
        });
        document.querySelectorAll('.eliminar').forEach(btn => {
            btn.addEventListener('click', eliminarProducto);
        });

        // Calcular el vuelto cuando el usuario ingrese el monto pagado
        document.getElementById('pago').addEventListener('input', function() {
            let montoPagado = parseFloat(this.value);
            let vuelto = montoPagado - total;
            if (!isNaN(vuelto) && vuelto >= 0) {
                document.getElementById('vuelto').value = vuelto.toFixed(2);
                document.getElementById('procesarVenta').hidden = false;
            } else {
                document.getElementById('vuelto').value = '0.00';
                document.getElementById('procesarVenta').hidden = true;
            }
        });
    }

    // Actualiza la cantidad de un producto en el carrito
    function actualizarCantidad(e) {
        const index = e.target.dataset.index;
        const nuevaCantidad = parseInt(e.target.value);
        const producto = carrito[index];

        if (nuevaCantidad > 0) {
            producto.cantidad = nuevaCantidad;
            producto.subtotal = producto.precio * nuevaCantidad;
        } else {
            carrito.splice(index, 1); // Elimina el producto si la cantidad es 0
        }

        actualizarCarrito();
    }

    // Elimina un producto del carrito
    function eliminarProducto(e) {
        const index = e.target.dataset.index;
        carrito.splice(index, 1);
        actualizarCarrito();
    }

    document.getElementById('procesarVenta').addEventListener('click', () => {
        if (carrito.length === 0) {
            alert('El carrito está vacío.');
            return;
        }
        // Confirmación antes de procesar la venta
        const confirmar = confirm('¿Estás seguro de procesar esta venta?');
        if (!confirmar) return;

        // Enviar datos al servidor (procesar_venta.php)
        fetch('../bd/procesar_venta.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(carrito) // Enviar el carrito como JSON
        })
        .then(response => response.json())  // Esperar la respuesta en formato JSON
        .then(data => {
            if (data.success) {
                alert('Venta procesada exitosamente.');
                location.reload();

            // Generar el ticket en PDF
            const { jsPDF } = window.jspdf;

            let total = data.total;  // El total de la venta, calculado previamente
            let pago = parseFloat(document.getElementById('pago').value) || 0;  // El monto pagado por el cliente, capturado del campo de pago
            let vuelto = pago - total;  // Calculamos el vuelto

            // Si el vuelto es negativo (es decir, no se ha pagado suficiente), lo dejamos en 0
            if (vuelto < 0) {
                vuelto = 0;
            }
            // Crear un nuevo documento PDF
            const doc = new jsPDF({
                orientation: "portrait",
                unit: "mm",
                format: [76, 210],  // Tamaño de página tipo ticket (76mm x 210mm)
            });

            let yPosition = 15; // Iniciar desde la parte superior de la página

            // Nombre de la tienda
            doc.setFontSize(14);
            doc.setFont("helvetica", "bold");
            doc.text("Multicomp Candelaria", 76 / 2, yPosition, { align: "center" });
            yPosition += 8;

            // Línea de separación
            doc.setFontSize(10);
            doc.setFont("helvetica", "bold");
            doc.text("----------------------------------------", 76 / 2, yPosition, { align: "center" });
            yPosition += 5;

            // Fecha y hora
            doc.setFontSize(8);
            doc.text(`Fecha: ${new Date().toLocaleString()}`, 8, yPosition);
            yPosition += 5;

            // Nombre del vendedor
            const nombreVendedor = "<?php echo $nombreu; ?>";
            doc.text(`Vendedor: ${nombreVendedor}`, 8, yPosition);
            yPosition += 5;

            // Línea de separación
            doc.text("----------------------------------------", 76 / 2, yPosition, { align: "center" });
            yPosition += 5;

            // Detalles del carrito (productos)
            let offset = yPosition; // Mantener el offset para los productos
            carrito.forEach(producto => {
                // Nombre y cantidad del producto
                doc.setFontSize(10);
                // Configuración del ancho máximo para el texto
                const maxWidth = 35;  // Ajusta esto según tu necesidad

                // Texto del producto
                const productText = `${producto.nombre} x ${producto.cantidad}`;

                // Utiliza splitTextToSize para ajustar el texto a la anchura máxima
                const splitText = doc.splitTextToSize(productText, maxWidth);

                // Dibuja el texto ajustado en el PDF
                doc.setFontSize(10);
                doc.text(splitText, 8, offset); // Ajusta la posición según sea necesario

                // O también podrías dividir la posición si quieres manejar un espaciado mayor entre líneas
                offset += splitText.length * 3; // Ajusta el espaciado entre líneas

                // Precio y subtotal
                doc.setFontSize(10);
                doc.text(`$${producto.subtotal.toFixed(2)}`, 65, offset, { align: "right" });
                doc.text(`Precio: $${producto.precio.toFixed(2)} c/u`, 8, offset + 5);

                offset += 12; // Espaciado entre productos
            });

            // Línea de separación antes del total
            doc.text("----------------------------------------", 76 / 2, offset, { align: "center" });
            offset += 5;
            
            // Total de la venta
            doc.setFontSize(10);
            doc.setFont("helvetica", "bold");
            doc.text(`TOTAL:`, 8, offset);
            doc.text(` $${data.total.toFixed(2)}`, 65, offset, { align: "right" });

            // Monto pagado
            doc.setFont("helvetica", "bold");
            offset += 6; // Incrementa la posición vertical
            doc.text(`Monto Pagado:`, 8, offset);
            doc.text(` $${pago.toFixed(2)}`, 65, offset, { align: "right" });

            // Vuelto
            offset += 6; // Incrementa la posición vertical
            doc.text(`Vuelto:`, 8, offset);
            doc.text(` $${vuelto.toFixed(2)}`, 65, offset, { align: "right" });

            
            offset += 6;

            // Línea de cierre
            doc.text("----------------------------------------", 76 / 2, offset, { align: "center" });
            offset += 5;

            // Información adicional (opcional)
            doc.setFontSize(8);
            doc.setFont("helvetica", "bold");
            doc.text("¡Gracias por tu compra!", 76 / 2, offset, { align: "center" });
            doc.text("Visítanos nuevamente.", 76 / 2, offset + 5, { align: "center" });

            // Información de contacto
            doc.text("Tel: +503 7272-1224", 8, offset + 15);
            doc.text("Email: grupomulticompcandelaria@gmail.com", 8, offset + 20);

            // Configurar la zona horaria de El Salvador
            const fechaElSalvador = new Date().toLocaleString("es-SV", {
                timeZone: "America/El_Salvador",
                dateStyle: "short",
                timeStyle: "medium",
            }).replace(/[\/:]/g, '-').replace(/ /g, '_');

            // Crear y generar el PDF
            doc.autoPrint(); // Preparar para impresión
            doc.output('dataurlnewwindow'); // Mostrar el PDF en una nueva ventana

            // Generar el nombre del archivo
            const nombreArchivo = `venta_${fechaElSalvador}.pdf`;

            // Descargar automáticamente
            doc.save(nombreArchivo); 
            }
        else {
                        alert('Error al procesar la venta: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Hubo un error al procesar la venta. Por favor, intenta nuevamente.');
                    console.error(error);
                });
        });
</script>
<?php
require_once '../headfooter/footer.php';
?>