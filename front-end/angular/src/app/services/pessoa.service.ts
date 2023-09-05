import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { PessoaI } from '../PessoaI';

@Injectable({
  providedIn: 'root'
})
export class PessoaService {
  private apiUrl = 'http://localhost:3333/pessoa?login=joao.reis.vinte8';

  constructor(private http:HttpClient) { }

  getPessoa(): Observable<PessoaI[]>{
    return this.http.get<PessoaI[]>(this.apiUrl);
  }
}
