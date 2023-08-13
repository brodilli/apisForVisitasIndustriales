import React, { useEffect, useState } from "react";
import FullCalendar from "@fullcalendar/react";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";
import esLocale from "@fullcalendar/core/locales/es";
import Modal from "react-bootstrap/Modal";
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";

const Agenda = () => {
  const [events, setEvents] = useState([]);
  const [showModal, setShowModal] = useState(false);

  const [idVisita, setIdVisita] = useState("");
  const [idVehiculo, setIdVehiculo] = useState("");
  const [fecha, setFecha] = useState("");
  const [horaSalida, setHoraSalida] = useState("");
  const [horaLlegada, setHoraLlegada] = useState("");
  const [empresa, setEmpresa] = useState("");
  const [lugar, setLugar] = useState("");
  const [maestroResponsable, setMaestroResponsable] = useState("");
  const [numAlumnos, setNumAlumnos] = useState("");

  const cerrarSesion = () => {
    localStorage.removeItem("token");
    window.location.href = "/login";
  };

  window.addEventListener("popstate", () => {
    cerrarSesion();
  });

  const obtenerEventos = () => {
    fetch("http://localhost/ws-2/obtener_agenda.php")
      .then((resp) => resp.json())
      .then((json) => {
        const eventos = json.map((evento) => ({
          idVisita: evento.id_visita,
          idVehiculo: evento.id_vehiculo, // Convertir la fecha a tipo Date
          fecha: evento.fecha, // Convertir la fecha a tipo Date
        }));
        setEvents(eventos);
      });
  };

  useEffect(() => {
    obtenerEventos();
  }, []);
  const handleCloseModal = () => {
    setShowModal(false);
  };

  const obtenerVisita = (id_visita) => {
    fetch("http://localhost/ws-2/obtener_solicitud_visita.php?id=" + id_visita)
      .then((resp) => resp.json())
      .then((json) => {
        const visita = json[0];

        setFecha(visita.fecha);
        setHoraSalida(visita.hora_salida);
        setHoraLlegada(visita.hora_llegada);
        setEmpresa(visita.empresa);
        setLugar(visita.lugar);
        setMaestroResponsable(
          visita.usuario.nombres +
            " " +
            visita.usuario.apellidoP +
            " " +
            visita.usuario.apellidoM
        );
        setNumAlumnos(parseInt(visita.num_alumnos + visita.num_alumnas));

        setShowModal(true);
      });
  };

  const handleDateSelect = (selectInfo) => {
    setShowModal(true);
    console.log(selectInfo);
    // setFecha(selectInfo.startStr);
    // setHoraSalida(selectInfo.startStr);
    // setHoraLlegada(selectInfo.startStr);
    // setEmpresa(selectInfo.startStr);
    // setLugar(selectInfo.startStr);
    // setMaestroResponsable(selectInfo.startStr);
    // setNumAlumnos(selectInfo.startStr);
    // setIdVehiculo(selectInfo.startStr);
    // setIdVisita(selectInfo.startStr);
  };

  const handleAddEvent = () => {
    const newEvent = {
      idVisita: idVisita,
      idVehiculo: idVehiculo,
      fecha: fecha,
      horaSalida: horaSalida,
      horaLlegada: horaLlegada,
      nombre_empresa: empresa,
      lugar: lugar,
      nombres: maestroResponsable,
      numAlumnos: numAlumnos,
      title: idVisita,
    };

    const eventsOnSameDay = events.filter((event) => {
      const eventDate = new Date(event.start);
      return (
        eventDate.getFullYear() === newEvent.start.getFullYear() &&
        eventDate.getMonth() === newEvent.start.getMonth() &&
        eventDate.getDate() === newEvent.start.getDate()
      );
    });

    if (eventsOnSameDay.length === 0) {
      newEvent.color = "green";
    } else if (eventsOnSameDay.length === 1) {
      newEvent.color = "blue";
    } else if (eventsOnSameDay.length === 2) {
      newEvent.color = "orange";
    } else if (eventsOnSameDay.length >= 3) {
      newEvent.color = "red";
    }

    setEvents([...events, newEvent]);

    fetch("http://localhost/ws-2/insertar_agenda.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_visita: idVisita,
        id_vehiculo: idVehiculo,
        fecha: fecha,
        horaSalida: horaSalida,
        horaLlegada: horaLlegada,
        docente: empresa,
        lugar: lugar,
        nombres: maestroResponsable,
        numAlumnos: numAlumnos,
        color: newEvent.color,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Success:", data);
      })
      .catch((error) => {
        console.error("Error:", error);
      });

    setShowModal(false);
  };

  return (
    <div>
      <FullCalendar
        plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
        initialView="dayGridMonth"
        headerToolbar={{
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay",
        }}
        events={events}
        locale={esLocale}
        height={"90vh"}
        selectable={true}
        selectMirror={true}
        dayMaxEvents={true}
        select={handleDateSelect}
      />
      <Modal show={showModal} onHide={handleCloseModal}>
        <Modal.Header closeButton>
          <Modal.Title>Agregar evento</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form>
            <Form.Group controlId="eventTitle">
              <Form.Label>id_solicitud</Form.Label>
              <Form.Control
                type="text"
                value={idVisita}
                onChange={obtenerVisita(idVisita)}
              />
            </Form.Group>
            <Form.Group controlId="idVehiculo">
              <Form.Label>ID Vehiculo</Form.Label>
              <Form.Control
                type="text"
                value={idVehiculo}
                onChange={(e) => setIdVehiculo(e.target.value)}
              />
            </Form.Group>
            <Form.Group controlId="fecha">
              <Form.Label>Fecha</Form.Label>
              <Form.Control type="text" value={fecha} disabled />
            </Form.Group>
            <Form.Group controlId="horaSalida">
              <Form.Label>Hora Salida</Form.Label>
              <Form.Control type="text" value={horaSalida} disabled />
            </Form.Group>
            <Form.Group controlId="horaLlegada">
              <Form.Label>Hora aprox. de Llegada a la empresa</Form.Label>
              <Form.Control type="text" value={horaLlegada} disabled />
            </Form.Group>
            <Form.Group controlId="empresa">
              <Form.Label>Empresa</Form.Label>
              <Form.Control type="text" value={empresa} disabled />
            </Form.Group>
            <Form.Group controlId="lugar">
              <Form.Label>Lugar</Form.Label>
              <Form.Control type="text" value={lugar} disabled />
            </Form.Group>

            <Form.Group controlId="maestroResponsable">
              <Form.Label>Maestro Responsable</Form.Label>
              <Form.Control type="text" value={maestroResponsable} disabled />
            </Form.Group>
            <Form.Group controlId="numAlumnos">
              <Form.Label>Numero de Alumnos</Form.Label>
              <Form.Control type="text" value={numAlumnos} disabled />
            </Form.Group>
          </Form>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleCloseModal}>
            Cancelar
          </Button>
          <Button variant="primary" onClick={handleAddEvent}>
            Agregar
          </Button>
        </Modal.Footer>
      </Modal>
    </div>
  );
};

export default Agenda;

// import React, { useEffect, useState } from "react";
// import FullCalendar from "@fullcalendar/react";
// import dayGridPlugin from "@fullcalendar/daygrid";
// import interactionPlugin from "@fullcalendar/interaction";
// import timeGridPlugin from "@fullcalendar/timegrid";
// import esLocale from "@fullcalendar/core/locales/es";
// import Modal from "react-bootstrap/Modal";
// import Button from "react-bootstrap/Button";
// import Form from "react-bootstrap/Form";

// const Agenda = () => {
//   const [events, setEvents] = useState([]);
//   const [showModal, setShowModal] = useState(false);
//   const [eventTitle, setEventTitle] = useState("");
//   const [eventStart, setEventStart] = useState("");
//   const [eventEnd, setEventEnd] = useState("");

//   const obtenerEventos = () => {
//     fetch("http://localhost/ws-2/obtener_agenda.php")
//       .then((resp) => resp.json())
//       .then((json) => {
//         const eventos = json.map((evento) => ({
//           title: evento.titulo,
//           start: evento.inicio,
//           end: evento.fin,
//         }));
//         setEvents(eventos);
//       });
//   };

//   useEffect(() => {
//     obtenerEventos();
//   }, []);
//   const handleCloseModal = () => {
//     setShowModal(false);
//   };

//   const handleDateSelect = (selectInfo) => {
//     setShowModal(true);
//     setEventTitle("");
//     setEventStart(selectInfo.startStr);
//     setEventEnd(selectInfo.endStr);
//   };

//   const handleAddEvent = () => {
//     const newEvent = {
//       title: eventTitle,
//       start: new Date(eventStart),
//       end: new Date(eventEnd),
//     };
//     setEvents([...events, newEvent]);

//     fetch("http://localhost/ws-2/insertar_agenda.php", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/json",
//       },
//       body: JSON.stringify({
//         id_visita: eventTitle,
//         inicio: eventStart,
//         fin: eventEnd,
//       }),
//     })
//       .then((response) => response.json())
//       .then((data) => {
//         console.log("Success:", data);
//       })
//       .catch((error) => {
//         console.error("Error:", error);
//       });

//     setShowModal(false);
//   };

//   return (
//     <div>
//       <FullCalendar
//         plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
//         initialView="dayGridMonth"
//         headerToolbar={{
//           left: "prev,next today",
//           center: "title",
//           right: "dayGridMonth,timeGridWeek,timeGridDay",
//         }}
//         events={events}
//         locale={esLocale}
//         height={"90vh"}
//         selectable={true}
//         selectMirror={true}
//         dayMaxEvents={true}
//         select={handleDateSelect}
//       />
//       <Modal show={showModal} onHide={handleCloseModal}>
//         <Modal.Header closeButton>
//           <Modal.Title>Agregar evento</Modal.Title>
//         </Modal.Header>
//         <Modal.Body>
//           <Form>
//             <Form.Group controlId="eventTitle">
//               <Form.Label>Título</Form.Label>
//               <Form.Control
//                 type="text"
//                 value={eventTitle}
//                 onChange={(e) => setEventTitle(e.target.value)}
//               />
//             </Form.Group>
//             <Form.Group controlId="eventStart">
//               <Form.Label>Inicio</Form.Label>
//               <Form.Control type="text" value={eventStart} disabled />
//             </Form.Group>
//             <Form.Group controlId="eventEnd">
//               <Form.Label>Fin</Form.Label>
//               <Form.Control type="text" value={eventEnd} disabled />
//             </Form.Group>
//           </Form>
//         </Modal.Body>
//         <Modal.Footer>
//           <Button variant="secondary" onClick={handleCloseModal}>
//             Cancelar
//           </Button>
//           <Button variant="primary" onClick={handleAddEvent}>
//             Agregar
//           </Button>
//         </Modal.Footer>
//       </Modal>
//     </div>
//   );
// };

// export default Agenda;
