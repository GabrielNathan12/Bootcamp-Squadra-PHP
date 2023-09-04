import { ComponentFixture, TestBed } from '@angular/core/testing';

import { UfComponent } from './uf.component';

describe('UfComponent', () => {
  let component: UfComponent;
  let fixture: ComponentFixture<UfComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [UfComponent]
    });
    fixture = TestBed.createComponent(UfComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
