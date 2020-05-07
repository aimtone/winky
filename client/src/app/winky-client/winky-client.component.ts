import { Component, OnInit, ViewEncapsulation, Input } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { SocketService } from '../services/socket.service';
import { WinkyService } from '../services/winky.service';
import { Socket } from 'ngx-socket-io';
import { v4 as uuidv4 } from 'uuid';

@Component({
  selector: 'winky-client',
  templateUrl: './winky-client.component.html',
  styleUrls: ['./winky-client.component.css'],
  encapsulation: ViewEncapsulation.ShadowDom,
})
export class WinkyClientComponent implements OnInit {
  @Input() uuid: string;
  isFirstOpening: boolean = false;
  isOpen: boolean = true;
  message: string = '';
  pulse: any;

  /* status
   * 0 : No entregado por error
   * 1 : enviando
   * 2 : recibido por el servidor
   * 3 : entregado
   * 4 : leido
   */
  conversation: any[] = [
    /*{
      messages: [
        {
          text:
            'Hola, mi nombre es Maria, soy tu asesora para lo que necesites',
          guid: 'af49076d-a315-497e-b903-afdf71bcce81',
          status: 0,
        },
      ],
      sender: 'robot',
      name: 'Maria',
      time: '10:54',
      type: 'message',
      image:
        'https://image.freepik.com/foto-gratis/retrato-mujer-call-center_23-2148094922.jpg',
      guid: 'af49076d-a315-497e-b903-afdf71bcce81',
    },
    {
      messages: [
        {
          text: 'Quiero hablar con un operador rea',
          guid: '48dacb4c-19a1-4e77-bf18-d6f6cece5a85',
          status: 4,
        },
      ],
      sender: 'me',
      name: '',
      time: '10:58',
      type: 'message',
      image: '',
      guid: 'af49076d-a315-497e-b903-afdf71bcce82',
    },
    {
      messages: [
        {
          text: 'Juan se ha conectado',
          guid: '7b553567-a7d1-4f26-9ccc-0e63a4654a25',
          status: 3,
        },
      ],
      sender: 'operator',
      name: 'Juan',
      time: '11:00',
      type: 'info',
      image: '',
    },
    {
      messages: [
        {
          text: 'Hola soy juan operador reak',
          guid: '053c80c9-8835-47a3-a974-74130cb0e4b2',
          status: 4,
        },
      ],
      sender: 'operator',
      name: 'Juan',
      time: '11:00',
      type: 'message',
      image:
        'https://img.freepik.com/foto-gratis/operador-call-center-hombre-auriculares-trabajando_93675-26787.jpg?size=626&ext=jpg',
    },*/
  ];
  locales: [
    {
      language: 'English';
      code: 'en';
    },
    {
      language: 'Español';
      code: 'es';
    }
  ];

  winky_data = null;

  constructor(
    private translate: TranslateService,
    private socketService: SocketService,
    private winkyServices: WinkyService,
    private socket: Socket
  ) {
    translate.setDefaultLang('en');
  }

  async ngOnInit() {
    let data: any = await this.winkyServices.getData(this.uuid).toPromise();
    if (data.data != null) {
      this.winky_data = data.data[0];
      let addClient = await this.socketService.addClient(this.uuid);

      this.socket.on('new operator message', (data) => {
        if (this.conversation.length == 0) {
          this.conversation.push(data.data);
        } else {
          let time = this.conversation.findIndex(
            (element) =>
              data.data.time == element.time && data.data.sender == 'operator'
          );

          let last_message = this.conversation[this.conversation.length - 1];

          if (
            last_message.time === data.data.time &&
            last_message.sender === data.data.sender
          ) {
            last_message.messages.push(data.data.messages[0]);
          } else {
            this.conversation.push(data.data);
          }
        }
      });
    }
  }

  changeLocale(language: string) {
    this.translate.use(language);
  }

  toggleOpen() {
    this.isOpen = !this.isOpen;
  }

  async sendMessage(e) {
    let last_message =
      this.conversation.length != 0
        ? this.conversation[this.conversation.length - 1]
        : null;
    if (e.type == 'click') {
      //presiona enbootn
    }
    if (e.type == 'keypress' && e.keyCode == 13) {
      if (this.message != '') {
        let payload = {
          messages: [
            {
              text: this.message,
              guid: uuidv4(),
              status: 1,
            },
          ],
          sender: 'me',
          name: '',
          time: '',
          type: 'message',
          image: '',
          guid: uuidv4(),
        };

        this.conversation.push(payload);

        this.message = '';

        let data = await this.socketService.sendMessage(payload);
        if (data['success']) {
          let element = this.conversation.filter(
            (e) => e.guid == data['data'].guid
          );

          setTimeout(() => {
            if (last_message != null) {
              // evaluar si el mensaje recibido del callback debe añadirse a la hora delultimo
              // mensaje o no
              console.log(last_message, data['data']);
              if (
                data['data'].time === last_message.time &&
                data['data'].sender === last_message.sender
              ) {
                // agregarlo a ese mensaje
                let index = this.conversation.findIndex(
                  (element) => element.guid == data['data'].guid
                );
                console.log(index);
                this.conversation.splice(index, 1);

                last_message.messages.push(data['data'].messages[0]);
              } else {
                // hacer un push sobre mensaje origianl
                Object.assign(element[0], data['data']);
              }
            } else {
              Object.assign(element[0], data['data']);
            }
          }, 1000);

          // console.log(this.conversation);
          /*let time = this.conversation.findIndex(
            (element) =>
              element.time == data['data'].time && element.sender == 'me'
          );

          // console.log(time)

          setTimeout(() => {
            if (time_exist.length == 0) {
              Object.assign(e[0], data['data']);
            } else {
              if(time_exist[0].sender == "me") {
                let index = this.conversation.findIndex(
                  (element) => element.guid == data['data'].guid
                );
                console.log(index)
                this.conversation.splice(index, 1);
                time_exist[0].messages.push(data['data'].messages[0]);
              }
              
            }
          }, 1000);
          */
        }
      }
    }
  }
}
