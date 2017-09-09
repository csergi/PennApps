import { TestBed, inject } from '@angular/core/testing';

import { QuestionserviceService } from './questionservice.service';

describe('QuestionserviceService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [QuestionserviceService]
    });
  });

  it('should be created', inject([QuestionserviceService], (service: QuestionserviceService) => {
    expect(service).toBeTruthy();
  }));
});
