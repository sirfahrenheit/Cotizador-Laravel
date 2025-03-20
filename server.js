// server.js (usando ES Modules)
import express from 'express';
import { createServer } from 'http';
import { Server } from 'socket.io';
import bodyParser from 'body-parser';

const app = express();
const server = createServer(app);
const io = new Server(server, {
  cors: { origin: "*" } // Ajusta según tus necesidades en producción
});

app.use(bodyParser.json());

app.post('/notify', (req, res) => {
  const data = req.body;
  // Emitir el evento a todos los clientes conectados
  io.emit('workOrderUpdated', data);
  res.sendStatus(200);
});

io.on('connection', (socket) => {
  console.log('Nuevo cliente conectado: ' + socket.id);
  socket.on('disconnect', () => {
    console.log('Cliente desconectado: ' + socket.id);
  });
});

server.listen(3000, () => {
  console.log('Servidor Node.js corriendo en puerto 3000');
});
