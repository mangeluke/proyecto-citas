<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css' rel='stylesheet' />
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Appointment Edit') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-4 lg:px-5">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg p-8">
                <div class="text-gray-900 dark:text-white">
                    <div class="container">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form method="POST" action="{{ route('updatecita.update', $agenda->id) }}">
                            @method('PUT')
                            @csrf
                            <div class="space-y-6">

                                <!-- Nombres -->
                                <div class="flex flex-col">
                                    <label for="nombres" class="font-semibold text-lg text-gray-700 dark:text-gray-300">Nombres</label>
                                    <input id="nombres" type="text" name="nombres" value="{{ old('nombres', $agenda->nombres) }}" class="mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                </div>

                                <!-- Correo electrónico -->
                                <div class="flex flex-col">
                                    <label for="correo" class="font-semibold text-lg text-gray-700 dark:text-gray-300">Correo electrónico</label>
                                    <input id="correo" type="email" name="correo" value="{{ old('correo', $agenda->correo) }}" class="mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                </div>

                                <!-- Teléfono -->
                                <div class="flex flex-col">
                                    <label for="telefono" class="font-semibold text-lg text-gray-700 dark:text-gray-300">Teléfono</label>
                                    <input id="telefono" type="tel" name="telefono" value="{{ old('telefono', $agenda->telefono) }}" class="mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                </div>

                                <!-- Tipo de servicio -->
                                <div class="flex flex-col">
                                    <label for="tiposervicio" class="font-semibold text-lg text-gray-700 dark:text-gray-300">Tipo de servicio</label>
                                    <select id="tiposervicio" name="tiposervicio" class="mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                        <option value="peluqueria" {{ $agenda->tiposervicio == 'peluqueria' ? 'selected' : '' }}>Peluqueria</option>
                                        <option value="barberia" {{ $agenda->tiposervicio == 'barberia' ? 'selected' : '' }}>Barberia</option>
                                        <option value="facial" {{ $agenda->tiposervicio == 'facial' ? 'selected' : '' }}>Facial</option>
                                    </select>
                                </div>

                                <!-- Nombre del empleado -->
                                <div class="flex flex-col">
                                    <label for="empleado_id" class="font-semibold text-lg text-gray-700 dark:text-gray-300">Empleado</label>
                                    <select id="empleado_id" class="form-select mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" name="empleado_id" required>
                                        <option selected disabled>Seleccione un Empleado</option>
                                        @foreach ($lempleado as $empleado)
                                            <option value="{{ $empleado->id }}" {{ $empleado->id == $agenda->empleado_id ? 'selected' : '' }}>{{ $empleado->nombres }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Fecha disponible -->
                                <div class="flex flex-col">
                                    <label for="fecha" class="font-semibold text-lg text-gray-700 dark:text-gray-300">Fecha disponible</label>
                                    <input id="fecha" type="datetime-local" name="fecha" value="{{ old('fecha', Carbon\Carbon::parse($agenda->fecha)->format('Y-m-d\TH:i')) }}" class="mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                </div>

                                <!-- Contenedor para el calendario -->
                                <div id='calendar-container' style="display: none; margin-top: 20px;">
                                    <div id='calendar' style="max-width: 100%; background: #f0f0f0;"></div>
                                </div>

                                <!-- Botón de enviar -->
                                <div class="flex flex-col mt-4">
                                    <button type="submit" class="btn btn-secondary btn-lg">Actualizar Datos</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const dateInput = document.getElementById('fecha');
        const empleadoSelect = document.getElementById('empleado_id');
        const calendarContainer = document.getElementById('calendar-container');
        
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            editable: true,
            selectable: true,
            dateClick: function(info) {
                const selectedDate = info.date;
                const timezoneOffset = selectedDate.getTimezoneOffset() * 60000;
                const adjustedDate = new Date(selectedDate.getTime() - timezoneOffset);
                const ahora = new Date();
                if (adjustedDate < ahora) {
                    alert('No se puede seleccionar una fecha y hora anteriores a la actual.');
                    return;
                }
                const formattedDate = adjustedDate.toISOString().slice(0, 16);
                dateInput.value = formattedDate;
                calendarContainer.style.display = 'none';
            },
            select: function(selectionInfo) {
                const startDate = selectionInfo.start;
                const endDate = selectionInfo.end;
                const isOccupied = occupiedDates.some(occupied => {
                    return (
                        (startDate >= new Date(occupied.start) && startDate < new Date(occupied.end)) ||
                        (endDate > new Date(occupied.start) && endDate <= new Date(occupied.end))
                    );
                });

                if (isOccupied) {
                    alert('La fecha y hora seleccionadas ya están ocupadas. Por favor elige otra.');
                    calendar.unselect();
                } else {
                    const formattedDate = startDate.toISOString().slice(0, 16);
                    dateInput.value = formattedDate;
                    calendarContainer.style.display = 'none';
                }
            }
        });

        let occupiedDates = [];

        empleadoSelect.addEventListener('change', function() {
            const empleadoId = empleadoSelect.value;
            if (empleadoId) {
                fetch(`{{ route('agendacita.ocupadas') }}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: new URLSearchParams({ empleado_id: empleadoId })
                })
                .then(response => response.json())
                .then(data => {
                    occupiedDates = data;
                    calendar.removeAllEvents();
                    calendar.addEventSource(data);
                })
                .catch(error => console.error('Error:', error));
            }
        });

        dateInput.addEventListener('click', function() {
            calendar.render();
            calendarContainer.style.display = 'block';
        });

        document.addEventListener('click', function(event) {
            if (!calendarContainer.contains(event.target) && event.target !== dateInput) {
                calendarContainer.style.display = 'none';
            }
        });
    });

    document.getElementById('booking-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok.');
        })
        .then(data => {
            Swal.fire({
                title: '¡Éxito!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                document.getElementById('booking-form').reset();
                window.location.href = '{{ route("agendacita.index") }}';
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al agendar la cita.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
    });

    </script>

    <style>
        /* Estilo adicional para el calendario */
        #calendar {
            border: 1px solid #ccc; /* Borde gris */
            border-radius: 8px; /* Esquinas redondeadas */
            overflow: hidden; /* Oculta contenido que sobresalga */
            background: #f8f9fa; /* Fondo gris claro */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra sutil */
        }

        .fc {
            font-family: 'Arial', sans-serif;
            font-size: 1em; 
        }

        /* Cabecera del calendario */
        .fc-toolbar {
            background-color: #6c757d; /* Fondo gris */
            color: white; /* Texto blanco */
            padding: 10px; /* Espaciado interno */
            border-radius: 8px; /* Esquinas redondeadas */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Sombra */
        }

        .fc-toolbar-title {
            font-size: 2em; 
            font-weight: bold; 
        }

        /* Estilo de los días del calendario */
        .fc-daygrid-day {
            border-radius: 60px;
            transition: background-color 0.3s, transform 0.2s; 
            padding: 10px; /* Espaciado interno */
        }

        .fc-daygrid-day:hover {
            background-color: rgba(108, 117, 125, 0.3); /* Color gris claro al pasar el mouse */
            transform: scale(1.05); /* Efecto de aumento */
        }

        .fc-daygrid-day.fc-day-today {
            background-color: rgba(108, 117, 125, 0.5); /* Fondo gris claro para el día actual */
            border: 2px solid #6c757d; /* Borde gris */
        }

        .fc-button {
            background-color: #28a745; /* Color verde para los botones */
            color: white;
            font-size: 1em; 
            border: none;
            border-radius: 5px; 
            padding: 10px 15px; 
        }

        .fc-button:hover {
            background-color: #218838; /* Color verde oscuro al pasar el mouse */
        }
    </style>
    
</x-app-layout>
