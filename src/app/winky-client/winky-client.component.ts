import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'winky-client',
  templateUrl: './winky-client.component.html',
  styleUrls: ['./winky-client.component.css'],
  encapsulation: ViewEncapsulation.ShadowDom
})
export class WinkyClientComponent implements OnInit {
  open: boolean = false;
  pulse: any;
  conversation: any[] = [
    {
      messages: [
        'Hola, mi nombre es Maria, soy tu asesora para lo que necesites',
        '¿En que puedo ayudarte?'
      ],
      sender: 'robot',
      name: 'Maria',
      time: '10:54',
      type: 'message',
      image: 'https://image.freepik.com/foto-gratis/retrato-mujer-call-center_23-2148094922.jpg'
    },
    {
      messages: ['¿Serias tan amable de comunicarme con operador real?, por favor', "HOL"],
      sender: 'me',
      name: '',
      time: '10:58',
      type: 'message',
      image: ''
    },
    {
      messages: [
        'En seguida, esto puede demorar unos minutos',
        'Mantente en linea'
      ],
      sender: 'robot',
      name: 'Maria',
      time: '10:59',
      type: 'message',
      image: 'https://image.freepik.com/foto-gratis/retrato-mujer-call-center_23-2148094922.jpg'
    },
    {
      messages: ["Juan se ha conectado"],
      sender: 'operator',
      name: 'Juan',
      time: '11:00',
      type: 'info',
      image: ''
    },
    {
      messages: [
        'Hola, soy Juan, operador real',
        '¿En que puedo ayudarte?'
      ],
      sender: 'operator',
      name: 'Juan',
      time: '11:00',
      type: 'message',
      image: 'https://img.freepik.com/foto-gratis/operador-call-center-hombre-auriculares-trabajando_93675-26787.jpg?size=626&ext=jpg'
    },
    {
      messages: ['Ya lo resolvi, muchas gracias'],
      sender: 'me',
      name: '',
      time: '11:01',
      type: 'message',
      image: ''
    },
    {
      messages: [
        'Por nada,estamos a la orden'
      ],
      sender: 'operator',
      name: 'Juan',
      time: '11:02',
      type: 'message',
      image: 'https://img.freepik.com/foto-gratis/operador-call-center-hombre-auriculares-trabajando_93675-26787.jpg?size=626&ext=jpg'
    },
    {
      messages: ["Juan se ha desconectado"],
      sender: 'operator',
      name: 'Juan',
      time: '11:02',
      type: 'info',
      image: ''
    },
    {
      messages: ['Ya lo resolvi, muchas gracias'],
      sender: 'me',
      name: '',
      time: '11:01',
      type: 'message',
      image: ''
    },
    {
      messages: [
        'Por nada,estamos a la orden'
      ],
      sender: 'operator',
      name: 'Juan',
      time: '11:02',
      type: 'message',
      image: 'https://img.freepik.com/foto-gratis/operador-call-center-hombre-auriculares-trabajando_93675-26787.jpg?size=626&ext=jpg'
    },
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

  constructor(private translate: TranslateService) {
    translate.setDefaultLang('en');
  }

  ngOnInit(): void {}

  changeLocale(language: string) {
    this.translate.use(language);
  }
}
