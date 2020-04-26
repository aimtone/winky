'use strict'
//requiriendo dependencias 
const express = require('express')
const socketio = require('socket.io')
const http = require('http')
const moment = require('moment');
const app = express() //instancia de express
const server = http.createServer(app) //creando el server con http y express como handle request
const io = socketio(server) //iniciando el server de socket.io
const PORT = process.env.PORT || 3000

//corriendo el servidor
server.listen(PORT, () => {
    console.log(`Server running in http://localhost:${PORT}`)
})

let usernames = [];
let numUsers = 0;

io.on('connection', (socket) => {


    socket.on('add user', (room, callback) => {
        socket.username = { ip: socket.request.connection.remoteAddress, socket_id: [], conversation: [] };

        let username = usernames.filter(username => username.ip == socket.username.ip);

        if (username.length == 0) {
            socket.username.socket_id.push(socket.id);
            usernames.push(socket.username);
            ++numUsers;
            //enviar notificacion de que un usuario nuevo se ha conectado
        } else {
            socket.username.socket_id = username[0].socket_id;
            socket.username.socket_id.push(socket.id);
            username[0] = socket.username;
        }
        socket.join(room);
        console.log(socket.username)
        callback(socket.username)
    });

    socket.on('new message', (data, callback) => {
        data.time = moment().format('h:mm');
        data.messages.map(element => {
            element.status = 2;
            console.log(element)
        })

        let time_exist = socket.username.conversation.filter(element => element.time == data.time);

        if (time_exist.length == 0) {
            socket.username.conversation.push(data);
        } else {
            time_exist[0].messages.push(data.messages[0])
        }
        callback({ data: data, success: true });
    });

    socket.on('disconnect', () => {
        let username = usernames.filter(username => username.ip == socket.username.ip);
        if (username[0] !== undefined) {
            if (username[0].socket_id.length <= 1) {
                let index = usernames.findIndex(username => username.ip == socket.username.ip);
                usernames.splice(index, 1);
                --numUsers;
                // enviar notificacion de que el usuario se ha desconectado
            } else {
                let index = username[0].socket_id.indexOf(socket.id);
                if (index > -1) {
                    username[0].socket_id.splice(index, 1);
                }
            }
        }
    });


});