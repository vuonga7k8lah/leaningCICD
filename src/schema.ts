import { Schema } from "elementor-php-core";

export const schema: Schema = [
  {
    type: 'section',
    name: 'general',
    label: 'General',
    default: [],
    fields: [
      {
        type: 'select',
        default: 'variant1',
        name: 'variant',
        label: 'Variant',
        options: {
          variant1: 'Variant 1',
          variant2: 'Variant 2',
          variant3: 'Variant 3',
          variant4: 'Variant 4',
          variant5: 'Variant 5',
          variant6: 'Variant 6',
          variant7: 'Variant 7',
        },
      },
      {
        type: 'font',
        label: 'Title font',
        name: 'titleFont',
        default: '"Roboto", sans-serif',
      },
      {
        type: 'font',
        label: 'Text font',
        name: 'textFont',
        default: '"Roboto", sans-serif',
      },
      {
        type: 'array',
        default: [],
        name: 'content',
        label: 'Content',
        fields: [
          { type: 'icons', default: { value: 'far fa-address-book', library: 'fa-regular'  }, name: 'icon', label: 'Icon' },
          { type: 'text', default: 'This is a title', name: 'title', label: 'Title' },
          { type: 'wysiwyg', default: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In malesuada augue eu rhoncus vehicula', name: 'text', label: 'Text' },
          { type: 'color', default: '', name: 'backgroundColor', label: 'Background Color', condition: {general_variant: ['variant6']} },
          { type: 'number', default: 10, min: 0, max: 0, name: 'borderRadius', label: 'Border Radius' },
          { type: 'color', default: '', name: 'startIconBackgroundColor', label: 'Start Icon Background Color', condition: { general_variant: ['variant3', 'variant4']}},
          { type: 'color', default: '', name: 'endIconBackgroundColor', label: 'End Icon Background Color', condition: { general_variant: ['variant3', 'variant4']}},
          { type: 'color', default: '', name: 'iconBackgroundColor', label: 'Icon Background Color', condition: { general_variant: ['variant1', 'variant2', 'variant6']} },
          { type: 'color', default: '', name: 'borderIconColor', label: 'Border Icon Color', condition: { general_variant: ['variant7'] } },
          { type: 'color', default: '', name: 'iconColor', label: 'Icon Color' },
          { type: 'color', default: '', name: 'titleColor', label: 'Title Color' },
          { type: 'color', default: '', name: 'textColor', label: 'Text Color' },
        ]
      }
    ]
  },
  {
    type: 'section',
    name: 'responsive',
    label: 'Responsive',
    default: [],
    fields: [
      { type: 'number', name: 'lg', label: 'Large', min: 1, max: 10, default: 4 },
      { type: 'number', name: 'md', label: 'Medium', min: 1, max: 10, default: 3 },
      { type: 'number', name: 'sm', label: 'Small', min: 1, max: 10, default: 2 },
      { type: 'number', name: 'xs', label: 'Extra small', min: 1, max: 10, default: 1 },
      { type: 'number', name: 'gapLg', label: 'Gap Large', min: 0, max: 60, default: 30 },
      { type: 'number', name: 'gapMd', label: 'Gap Medium', min: 0, max: 60, default: 30 },
      { type: 'number', name: 'gapSm', label: 'Gap Small', min: 0, max: 60, default: 30 },
      { type: 'number', name: 'gapXs', label: 'Gap Extra Small', min: 0, max: 60, default: 30 }
    ],
  },
  {
    type: 'section',
    name: 'carousel',
    label: 'Carousel',
    default: [],
    fields: [
      { type: 'switcher', name: 'enable', label: 'Enable', default: false },
      { type: 'switcher', name: 'buttonEnable', label: 'Button Enable', default: true, condition: { carousel_enable: true } },
      { type: 'switcher', name: 'paginationEnable', label: 'Pagination Enable', default: true, condition: { carousel_enable: true } },
      { type: 'color', name: 'buttonColor', label: 'Button Color', default: '#000', condition: { carousel_enable: true } },
    ]
  },
];
