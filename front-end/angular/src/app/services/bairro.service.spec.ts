import { TestBed } from '@angular/core/testing';

import { BairroService } from './bairro.service';

describe('BairroService', () => {
  let service: BairroService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(BairroService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
