import { Component } from '@angular/core';
import { BairroI } from 'src/app/BairroI';
import { MunicipioI } from 'src/app/MunicipioI';
import { PessoaI } from 'src/app/PessoaI';
import { UfI } from 'src/app/UfI';
import { BairroService } from 'src/app/services/bairro.service';
import { MunicipioService } from 'src/app/services/municipio.service';
import { PessoaService } from 'src/app/services/pessoa.service';
import { UfService } from 'src/app/services/uf.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent {
  UfLista: UfI[] = [];
  MunicipioLista: MunicipioI[] = [];
  bairroLista: BairroI[] = [];
  pessoaLista: PessoaI[] = [];
  novoUf!: UfI;

  constructor(private ufService: UfService,
    private municipioService: MunicipioService, private bairroService: BairroService,private pessoaService: PessoaService){
    this.getUfs();
    this.getMunicipio();
    this.getBairro();
    this.getPessoa();

  }
  getUfs():void{
    this.ufService.getUf().subscribe((uf)=> {
      this.UfLista = uf;

    });
  }

  getMunicipio():void{
    this.municipioService.getMunicipio().subscribe((municipio) => (this.MunicipioLista = municipio));

  }
  getBairro():void{
    this.bairroService.getBairro().subscribe((bairro) => (this.bairroLista = bairro));

  }

  getPessoa(): void{
    this.pessoaService.getPessoa().subscribe((pessoa) => this.pessoaLista = pessoa);
  }
  enviarUf(novoUf: UfI): void{
    this.UfLista.push(novoUf);
  }
}

