import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { UfI } from '../UfI';
@Injectable({
  providedIn: 'root'
})

export class UfService {
  private apiUrl = 'http://localhost:3333/uf';

  constructor(private http: HttpClient) { }


  getUf(): Observable<UfI[]>{
    return this.http.get<UfI[]>(this.apiUrl);
  }
}

