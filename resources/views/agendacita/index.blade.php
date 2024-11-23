<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css' rel='stylesheet' />
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Appointment Booking') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-4 lg:px-5">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
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
                        <form method="POST" action="{{ route('agendacita.store') }}" id="booking-form">
                            @csrf
                            <div class="space-y-4">

                                <!-- Nombres -->
                                <div class="form-group">
                                    <label for="nombres">Nombres</label>
                                    <input id="nombres" type="text" name="nombres" class="form-control" required>
                                </div>

                                <!-- Correo electrónico -->
                                <div class="form-group">
                                    <label for="correo">Correo electrónico</label>
                                    <input id="correo" type="email" name="correo" class="form-control" required>
                                </div>

                                <!-- Teléfono -->
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input id="telefono" type="tel" name="telefono" class="form-control" required>
                                </div>

                                <!-- Tipo de servicio -->
                                <div class="form-group">
                                    <label for="tiposervicio">Tipo de servicio</label>
                                    <select id="tiposervicio" name="tiposervicio" class="form-control" required>
                                        <option value="peluqueria">Peluqueria</option>
                                        <option value="barberia">Barberia</option>
                                        <option value="belleza">Belleza</option>
                                        <option value="manicurista">Manicurista</option>
                                    </select>
                                </div>

                                <!-- Nombre del empleado -->
                                <div class="form-group">
                                    <label for="empleado_id">Empleado</label>
                                    <select id="empleado_id" name="empleado_id" class="form-control" required>
                                        <option selected disabled>Seleccione un Empleado</option>
                                        @foreach ($lempleado as $empleado)
                                            <option value="{{ $empleado->id }}">{{ $empleado->nombres }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Fecha disponible -->
                                <div class="form-group">
                                    <label for="fecha">Fecha disponible</label>
                                    <input id="fecha" type="datetime-local" name="fecha" class="form-control" required>
                                </div>

                                <!-- Contenedor para el calendario -->
                                <div id='calendar-container' style="display: none; margin-top: 20px;">
                                    <div id='calendar'></div>
                                </div>

                                <!-- Botón de enviar -->
                                <div class="form-group mt-4">
                                    <button type="submit" style="background: grey; border: gray;" class="btn btn-primary">Agendar Cita</button>
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
                const ahora = new Date();
                if (selectedDate < ahora) {
                    alert('No se puede seleccionar una fecha y hora anteriores a la actual.');
                    return;
                }
                dateInput.value = selectedDate.toISOString().slice(0, 16);
                calendarContainer.style.display = 'none';
            },
            select: function(selectionInfo) {
                const startDate = selectionInfo.start;
                const isOccupied = occupiedDates.some(occupied => {
                    return (startDate >= new Date(occupied.start) && startDate < new Date(occupied.end));
                });

                if (isOccupied) {
                    alert('La fecha y hora seleccionadas ya están ocupadas. Por favor elige otra.');
                    calendar.unselect();
                } else {
                    dateInput.value = startDate.toISOString().slice(0, 16);
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
        .then(response => response.json())
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
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .fc {
            font-family: 'Arial', sans-serif;
            font-size: 0.9em; 
        }

        .fc-toolbar {
            background-color: #ccc;
            color: #333;
        }

        .fc-toolbar-title {
            font-size: 1.5em; 
            font-weight: bold; 
        }

        .fc-daygrid-day {
            background-color: #e0e0e0; 
        }

        .fc-daygrid-day:hover {
            background-color: rgba(0, 123, 255, 0.2);
            transform: scale(1.05);
        }

        .fc-daygrid-day.fc-day-today {
            background-color: #b0b0b0; 
        }

        .fc-button {
            background-color: #28a745; 
            color: white;
            font-size: 0.9em; 
            border: none;
            border-radius: 5px; 
            padding: 5px 10px; 
        }

        .fc-button:hover {
            background-color: #218838; 
        }
        
    </style>

</x-app-layout>
