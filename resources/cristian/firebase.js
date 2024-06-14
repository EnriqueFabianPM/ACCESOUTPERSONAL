import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.2/firebase-app.js";
import {
  getFirestore,
  collection,
  getDocs,
  onSnapshot,
  addDoc,
  deleteDoc,
  doc,
  getDoc,
  updateDoc,
} from "https://www.gstatic.com/firebasejs/9.6.2/firebase-firestore.js";

const firebaseConfig = {
  apiKey: "AIzaSyC6QvtPoSkGPX2vmnv8GxTzD1yTOixqePU",
  authDomain: "accesos-ut.firebaseapp.com",
  projectId: "accesos-ut",
  storageBucket: "accesos-ut.appspot.com",
  messagingSenderId: "62584472022",
  appId: "1:62584472022:web:a5e48c0fb003511445e477",
  measurementId: "G-193NNYC6PG"
};

// Iniciar FireBase
export const app = initializeApp(firebaseConfig);
export const db = getFirestore();

/**
 * Save a New Task in Firestore
 * @param {string} nombre the name of the visitor
 * @param {string} motivo the reason for the visit
 * @param {string} fecha the date of the visit
 * @param {string} correo the email of the visitor
 * @param {string} telefono the phone number of the visitor
 */
export const saveTask = (nombre, motivo, fecha, correo, telefono) =>
  addDoc(collection(db, "Visitantes"), { nombre, motivo, fecha, correo, telefono });

/**
 * Get real-time updates of tasks from Firestore
 * @param {function} callback function to be called with the snapshot
 */
export const onGetTasks = (callback) =>
  onSnapshot(collection(db, "Visitantes"), callback);

/**
 * Delete a Task from Firestore
 * @param {string} id Task ID
 */
export const deleteTask = (id) => deleteDoc(doc(db, "Visitantes", id));

/**
 * Get a single Task from Firestore
 * @param {string} id Task ID
 * @returns {Promise<DocumentSnapshot>}
 */
export const getTask = (id) => getDoc(doc(db, "Visitantes", id));

/**
 * Update a Task in Firestore
 * @param {string} id Task ID
 * @param {object} newFields Object containing the fields to update
 * @returns {Promise<void>}
 */
export const updateTask = (id, newFields) =>
  updateDoc(doc(db, "Visitantes", id), newFields);

/**
 * Get all Tasks from Firestore
 * @returns {Promise<QuerySnapshot>}
 */
export const getTasks = () => getDocs(collection(db, "Visitantes"));
