import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { UfI } from 'src/app/UfI';

@Component({
  selector: 'app-uf',
  templateUrl: './uf.component.html',
  styleUrls: ['./uf.component.css']
})
export class UfComponent {
  @Output() onSumit = new EventEmitter<UfI>();
  @Input()
  ufForm!:FormGroup;

  ngOnInit(): void{
    this.ufForm = new FormGroup({
      id: new FormControl(''),
      nome: new FormControl('',[Validators.required]),
      sigla: new FormControl('',[Validators.required]),
      status: new FormControl('',[Validators.required])})
  }

  get nome(){
    return this.ufForm.get('nome')!;
  }
  get sigla(){
    return this.ufForm.get('sigla')!;
  }
  get status(){
    return this.ufForm.get('status')!;
  }
  enviar(){
    if(this.ufForm.invalid){
      return;
    }
    console.log(this.ufForm.value);
  }
}
