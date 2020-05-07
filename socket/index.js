"use strict";
//requiriendo dependencias
const express = require("express");
const socketio = require("socket.io");
const http = require("http");
const moment = require("moment");
const app = express(); //instancia de express
const server = http.createServer(app); //creando el server con http y express como handle request
const io = socketio(server); //iniciando el server de socket.io
const PORT = process.env.PORT || 3000;

//corriendo el servidor
server.listen(PORT, () => {
    console.log(`Server running in http://localhost:${PORT}`);
});

let usernames = [];
let numUsers = 0;

io.on("connection", (socket) => {
    socket.on("add user", (data, callback) => {
        socket.username = {
            ip: socket.request.connection.remoteAddress,
            socket_id: [],
            conversation: [],
            website: "",
        };
        socket.username.website = data.room;
        socket.username.operator =
            data.operator !== undefined ? data.operator : false;

        let username = usernames.filter(
            (username) => username.ip == socket.username.ip
        );

        if (username.length == 0) {
            socket.username.socket_id.push(socket.id);
            usernames.push(socket.username);
            ++numUsers;
            //enviar notificacion de que un usuario nuevo se ha conectado
            if (!socket.username.operator) {
                let operators = usernames.filter(
                    (username) => username.operator && username.website == data.room
                );
                operators.map((operator) => {
                    operator.socket_id.map((socket_id) => {
                        io.to(socket_id).emit("new user", socket.username);
                    });
                });
            }
        } else {
            socket.username.socket_id = username[0].socket_id;
            socket.username.socket_id.push(socket.id);
            username[0] = socket.username;
        }

        socket.join(data.room);
        console.log(socket.username);
        callback(socket.username);
    });

    socket.on("get connected clients", (callback) => {
        callback(usernames.filter(username => !username.operator && username.website == socket.username.website))
    });

    socket.on("new message", (data, callback) => {
        data.time = moment().format("h:mm");

        let time_exist = socket.username.conversation.filter(
            (element) => element.time == data.time
        );

        if (time_exist.length == 0) {
            socket.username.conversation.push(data);
        } else {
            time_exist[0].messages.push(data.messages[0]);
        }

        data.messages.map((element) => {
            element.status = 2;
        });

        // remitir mensaje a operadores del sitio web
        if (!socket.username.operator) {
            let operators = usernames.filter(
                (username) => username.operator && username.website == socket.username.website
            );
            operators.map((e) => {
                e.socket_id.map((socket_id) => {
                    io.to(socket_id).emit("new client message", { username: socket.username, data: data });
                    data.messages.map((element) => {
                        element.status = 3;
                    });
                });
            });
        } else {
            // remitir mensajes al cliente especifico al que se digire el mensaje
            let client = usernames.filter(
                (username) => username.ip == data.to && !username.operator && username.website == socket.username.website
            );

            if (client.length != 0) {
                //existe
                client.map((e) => {
                    e.socket_id.map((socket_id) => {
                        io.to(socket_id).emit("new operator message", { username: socket.username, data: data });
                        data.messages.map((element) => {
                            element.status = 3;
                        });
                    });
                });

            }
        }

        callback({ data: data, success: true });
    });

    socket.on("disconnect", () => {
        let username = usernames.filter(
            (username) => socket.username !== undefined && username.ip == socket.username.ip
        );
        if (username[0] !== undefined) {
            if (username[0].socket_id.length <= 1) {
                let index = usernames.findIndex(
                    (username) => username.ip == socket.username.ip
                );
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