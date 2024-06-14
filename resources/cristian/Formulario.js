import {
    onGetTasks,
    saveTask,
    deleteTask,
    getTask,
    updateTask,
  } from "./firebase.js";
  
  const taskForm = document.getElementById("task-form");
  const tasksContainer = document.getElementById("tasks-container");
  
  let editStatus = false;
  let id = "";
  
  window.addEventListener("DOMContentLoaded", async (e) => {
    onGetTasks((querySnapshot) => {
      tasksContainer.innerHTML = "";
  
      querySnapshot.forEach((doc) => {
        const task = doc.data();
  
        tasksContainer.innerHTML += `
          <div class="card card-body mt-2 border-primary">
              <h3 class="h5">${task.nombre}</h3>
              <p>${task.motivo}</p>
              <p>${task.fecha}</p>
              <p>${task.correo}</p>
              <p>${task.telefono}
              <div>
                  <button class="btn btn-primary btn-delete" data-id="${doc.id}">
                      Delete
                  </button>
                  <button class="btn btn-secondary btn-edit" data-id="${doc.id}">
                      Edit    
                  </button> 
              </div>
          </div>`;
      });
  
      const btnsDelete = tasksContainer.querySelectorAll(".btn-delete");
      btnsDelete.forEach((btn) => {
        btn.addEventListener("click", async ({ target: { dataset } }) => {
          const confirmation = confirm("¿Está seguro de eliminar su información de contacto?");
          if (confirmation) {
            try {
              await deleteTask(dataset.id);
            } catch (error) {
              console.log(error);
            }
          }
        });
      });
  
      const btnsEdit = tasksContainer.querySelectorAll(".btn-edit");
      btnsEdit.forEach((btn) => {
        btn.addEventListener("click", async (e) => {
          try {
            const doc = await getTask(e.target.dataset.id);
            const task = doc.data();
            taskForm["task-nombre"].value = task.nombre;
            taskForm["task-motivo"].value = task.motivo;
            taskForm["task-fecha"].value = task.fecha;
            taskForm["task-correo"].value = task.correo;
            taskForm["task-telefono"].value = task.telefono;
  
            editStatus = true;
            id = doc.id;
            taskForm["btn-task-form"].innerText = "Update";
          } catch (error) {
            console.log(error);
          }
        });
      });
    });
  });
  
  taskForm.addEventListener("submit", async (e) => {
    e.preventDefault();
  
    const nombre = taskForm["task-nombre"];
    const motivo = taskForm["task-motivo"];
    const fecha = taskForm["task-fecha"];
    const correo = taskForm["task-correo"];
    const telefono = taskForm["task-telefono"];
  
    try {
      if (!editStatus) {
        await saveTask(nombre.value, motivo.value, fecha.value, correo.value, telefono.value);
      } else {
        await updateTask(id, {
          nombre: nombre.value,
          motivo: motivo.value,
          fecha: fecha.value,
          correo: correo.value,
          telefono: telefono.value
        });
  
        editStatus = false;
        id = "";
        taskForm["btn-task-form"].innerText = "Save";
      }
  
      taskForm.reset();

      if(nombre.checkValidity()){
        nombre.focus()
      } else{
        console.log("Nombre inválido");
      }

    } catch (error) {
      console.log(error);
    }
  });
  
  document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('task-fecha');
    const errorMessage = document.getElementById('error-message');
    const errorMessages = document.getElementById('error-messages');
    const motivoSelect = document.getElementById('task-motivo');
    const motivoOtroContainer = document.getElementById('task-motivo-otro-container');
    const generateQRCodeBtn = document.getElementById('generate-qrcode-btn');
    const qrCodeContainer = document.getElementById('qr-code');
  
    // Initialize Flatpickr
    flatpickr(dateInput, {
      enableTime: true,
      dateFormat: "Y-m-d H:i",
      minDate: "today",
      maxDate: "2024-12-31",
      defaultDate: "today",
    });
  
    dateInput.addEventListener('input', function() {
      const selectedDate = new Date(dateInput.value);
      const minDate = new Date(dateInput.min);
  
      if (selectedDate.getDay() === 6 || selectedDate.getDay() === 0) {
        errorMessage.style.display = 'block';
        errorMessages.style.display = 'none';
        dateInput.value = '';
      } else {
        errorMessage.style.display = 'none';
        const selectedHour = selectedDate.getHours();
        if (selectedHour < 8 || selectedHour >= 17) {
          errorMessages.style.display = 'block';
          dateInput.value = '';
        } else {
          errorMessages.style.display = 'none';
        }
      }
    });
  
    motivoSelect.addEventListener('change', function() {
      if (motivoSelect.value === 'otro') {
        motivoOtroContainer.classList.remove('hidden');
      } else {
        motivoOtroContainer.classList.add('hidden');
      }
    });
  
    generateQRCodeBtn.addEventListener('click', function() {
      const formData = new FormData(document.getElementById('task-form'));
      let formDataString = '';
  
      for (const [key, value] of formData.entries()) {
        formDataString += `${key}: ${value}\n`;
      }
  
      new QRCode(qrCodeContainer, {
        text: formDataString,
        width: 200,
        height: 200
      });
    });
  });
  