import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WinkyClientComponent } from './winky-client.component';

describe('WinkyClientComponent', () => {
  let component: WinkyClientComponent;
  let fixture: ComponentFixture<WinkyClientComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WinkyClientComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WinkyClientComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
