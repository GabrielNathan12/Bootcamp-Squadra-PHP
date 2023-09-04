import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import {HttpClientModule} from '@angular/common/http'
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { UfComponent } from './components/uf/uf.component';
import { MunicipioComponent } from './components/municipio/municipio.component';
import { HeaderComponent } from './components/header/header.component';
import { FooterComponent } from './components/footer/footer.component';
import { BairroComponent } from './components/bairro/bairro.component';
import { PessoaComponent } from './components/pessoa/pessoa.component';
import { HomeComponent } from './components/home/home.component';

@NgModule({
  declarations: [
    AppComponent,
    UfComponent,
    MunicipioComponent,
    HeaderComponent,
    FooterComponent,
    BairroComponent,
    PessoaComponent,
    HomeComponent,

  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
