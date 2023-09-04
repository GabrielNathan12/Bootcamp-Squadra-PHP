import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { UfComponent } from './components/uf/uf.component';
import { MunicipioComponent } from './components/municipio/municipio.component';
import { BairroComponent } from './components/bairro/bairro.component';
import { PessoaComponent } from './components/pessoa/pessoa.component';
import { HomeComponent } from './components/home/home.component';

const routes: Routes = [
  {path:'', component:HomeComponent},
  {path:'uf', component:UfComponent},
  {path:'municipio', component:MunicipioComponent},
  {path:'bairro', component:BairroComponent},
  {path:'pessoa', component:PessoaComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
