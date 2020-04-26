import { BrowserModule } from '@angular/platform-browser';
import { NgModule, Injector, DoBootstrap, Injectable  } from '@angular/core';
import { FormsModule } from '@angular/forms'; 
import { SocketIoModule, SocketIoConfig } from 'ngx-socket-io';
import { AppRoutingModule } from './app-routing.module';
import { createCustomElement } from '@angular/elements';
import { WinkyClientComponent } from './winky-client/winky-client.component';
import {TranslateLoader, TranslateModule} from '@ngx-translate/core';
import {TranslateHttpLoader} from '@ngx-translate/http-loader';
import {HttpClient, HttpClientModule} from '@angular/common/http';

const config: SocketIoConfig = { url: 'http://192.168.1.94:3000', options: {transports: ['websocket'], upgrade: false} };

@NgModule({
  declarations: [
    WinkyClientComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    SocketIoModule.forRoot(config),
    AppRoutingModule,
    HttpClientModule,
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: HttpLoaderFactory,
                deps: [HttpClient]
            }
        }),
        
  ],
  providers: [],
  bootstrap: [
  ],
  entryComponents: [
    WinkyClientComponent
  ]
})

export class AppModule implements DoBootstrap {
  constructor(private injector: Injector) {
    const el1 = createCustomElement(WinkyClientComponent, {
      injector: this.injector
    });
    if (!customElements.get('winky-client')) {  
      customElements.define('winky-client', el1)
    }
   
  }
  ngDoBootstrap() {
  }
}

export function HttpLoaderFactory(http: HttpClient) {
  return new TranslateHttpLoader(http);
}
