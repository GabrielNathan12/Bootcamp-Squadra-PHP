import { Component, EventEmitter, Output, Input } from '@angular/core';
import { AbstractControl, FormControl, FormGroup, Validators } from '@angular/forms';
import { UfI } from 'src/app/UfI';
import { UfService } from 'src/app/services/uf.service';

@Component({
  selector: 'app-uf',
  templateUrl: './uf.component.html',
  styleUrls: ['./uf.component.css'],
})
export class UfComponent {
  @Output() onSubmit = new EventEmitter<UfI>();
  ufForm!: FormGroup;
  @Input() Uflista! : UfI[];

  constructor(private ufService: UfService) {}

  ngOnInit(): void {
    this.ufForm = new FormGroup({
      nome: new FormControl('', [Validators.required]),
      sigla: new FormControl('', [Validators.required]),
      status: new FormControl('', [Validators.required]),
    });
  }

  get nome() {
    return this.ufForm.get('nome')!;
  }
  get sigla() {
    return this.ufForm.get('sigla')!;
  }
  get status() {
    return this.ufForm.get('status')!;
  }
  validarNomes(){

  }
  enviar() {
    if (this.ufForm.invalid) {
      return;
    }

    const ufData: UfI = this.ufForm.value;
    this.ufService.postUf(ufData).subscribe((response) => {
      this.onSubmit.emit(ufData);
      this.ufForm.reset();
    });
  }


}
