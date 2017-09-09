import { trigger, state, animate, transition, style, keyframes } from '@angular/core';

export const slideUpDown =
  trigger('slideUpDown', [
    state('*', style({
      position: 'absolute',
      top: 0,
      left: 0,
      right: 0,
      bottom: 0,
      backgroundColor: 'rgba(0, 0, 0, 0)',
    })),

    transition('void => *', [
      style({
        backgroundColor: 'rgba(0, 0, 0, 0)',
        position: 'absolute',
      }),

      animate(400, keyframes([
        style({ opacity: 0, transform: 'translateY(90%)', offset:0 }),
        style({ opacity: 0.3, transform: 'translateY(85%)', offset: 0.2 }),
        style({ opacity: 0.5, transform: 'translateY(70%)', offset: 0.4 }),
        style({ opacity: 0.7, transform: 'translateY(45%)', offset: 0.7 }),
        style({ opacity: 0.8, transform: 'translateY(15%)', offset: 0.9 }),
        style({ opacity: 1, transform: 'translateY(0%)', offset: 1 })
      ]))
    ]),

    transition('* => void', [
      style({
        backgroundColor: 'rgba(0, 0, 0, 0)',
        position: 'absolute',
      }),

      animate(400, keyframes([
        style({ opacity: 0.8, transform: 'translateY(-15%)', offset: 0.2 }),
        style({ opacity: 0.5, transform: 'translateY(30%)', offset: 0.4 }),
        style({ opacity: 0.3, transform: 'translateY(70%)', offset: 0.7 }),
        style({ opacity: 0, transform: 'translateY(90%)', offset: 1 }),
      ]))

    ])
  ]);
