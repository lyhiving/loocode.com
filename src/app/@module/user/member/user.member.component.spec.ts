import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import {UserMemberComponent} from "./user.member.component";


describe('User.MemberComponent', () => {
  let component: UserMemberComponent;
  let fixture: ComponentFixture<UserMemberComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserMemberComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserMemberComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
