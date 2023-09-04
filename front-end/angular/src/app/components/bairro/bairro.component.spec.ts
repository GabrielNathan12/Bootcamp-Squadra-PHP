import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BairroComponent } from './bairro.component';

describe('BairroComponent', () => {
  let component: BairroComponent;
  let fixture: ComponentFixture<BairroComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [BairroComponent]
    });
    fixture = TestBed.createComponent(BairroComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
