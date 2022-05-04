export type Data = {
general: {
variant: string;
titleFont: string;
textFont: string;
content: {
icon: string | { url: string };
title: string;
text: string;
backgroundColor: string;
borderRadius: number;
startIconBackgroundColor: string;
endIconBackgroundColor: string;
iconBackgroundColor: string;
borderIconColor: string;
iconColor: string;
titleColor: string;
textColor: string;
}[];
};
responsive: {
lg: number;
md: number;
sm: number;
xs: number;
gapLg: number;
gapMd: number;
gapSm: number;
gapXs: number;
};
carousel: {
enable: boolean;
buttonEnable: boolean;
paginationEnable: boolean;
buttonColor: string;
};
};