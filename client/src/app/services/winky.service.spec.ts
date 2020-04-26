import { TestBed } from '@angular/core/testing';

import { WinkyService } from './winky.service';

describe('WinkyService', () => {
  let service: WinkyService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(WinkyService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
