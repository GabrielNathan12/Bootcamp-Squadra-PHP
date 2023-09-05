import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { MunicipioI } from '../MunicipioI';

@Injectable({
  providedIn: 'root'
})
export class MunicipioService {
  private apiUrl = 'http://localhost:3333/municipio';

  constructor(private http: HttpClient) { }

  getMunicipio(): Observable<MunicipioI[]>{
    return this.http.get<MunicipioI[]>(this.apiUrl);
  }
}
