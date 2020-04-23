import { BrowserModule } from '@angular/platform-browser';
import { NgModule, Injector, DoBootstrap  } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import  { createCustomElement } from '@angular/elements';
import { WinkyClientComponent } from './winky-client/winky-client.component';
import {TranslateLoader, TranslateModule} from '@ngx-translate/core';
import {TranslateHttpLoader} from '@ngx-translate/http-loader';
import {HttpClient, HttpClientModule} from '@angular/common/http';

@NgModule({
  declarations: [
    AppComponent,
    WinkyClientComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: HttpLoaderFactory,
                deps: [HttpClient]
            }
        })
  ],
  providers: [],
  bootstrap: [
  ],
  entryComponents: [
    AppComponent,
    WinkyClientComponent
  ]
})

export class AppModule implements DoBootstrap {
  constructor(private injector: Injector) {
    const el1 = createCustomElement(WinkyClientComponent, {
      injector: this.injector
    });
    customElements.define('winky-client', el1);
    //temporal
    const el2 = createCustomElement(AppComponent, {
      injector: this.injector
    });
    customElements.define('app-root', el2);
  }
  ngDoBootstrap() {
  }
}

export function HttpLoaderFactory(http: HttpClient) {
  return new TranslateHttpLoader(http);
}
