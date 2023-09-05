import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { BairroI } from '../BairroI';
@Injectable({
  providedIn: 'root'
})
export class BairroService {
  private apiUrl = 'http://localhost:3333/bairro';

  constructor(private http: HttpClient) { }

  getBairro(): Observable<BairroI[]>{
    return this.http.get<BairroI[]>(this.apiUrl);
  }
}
