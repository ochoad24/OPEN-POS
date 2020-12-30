export const eight={
    money:(number)=>{
        return 'Q ' + number.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    },
    percentage:(number)=>{
        return '% ' + number.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    },
    stock:(number)=>{
        return String(number.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
    }
}