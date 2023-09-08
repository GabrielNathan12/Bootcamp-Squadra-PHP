import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders  } from '@angular/common/http';
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

  postUf(dados: UfI){
    const headers  = new HttpHeaders({'Content-Type': 'application/json' });

    const body = JSON.stringify(dados);

    return this.http.post(this.apiUrl, body, {headers});

  }
}

