import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MunicipioComponent } from './municipio.component';

describe('MunicipioComponent', () => {
  let component: MunicipioComponent;
  let fixture: ComponentFixture<MunicipioComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [MunicipioComponent]
    });
    fixture = TestBed.createComponent(MunicipioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
