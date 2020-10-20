export default {
    computed:{

        getTagFor(){
            const expName = this.widget.explorer.explorer;
            let data = [];
            _.find(this.explorer, (exp) => {
                if(exp.name === expName){
                    data = exp.tagsFor
                }
            })
            return data
        },

        getNames(){
            const expName = this.widget.explorer.explorer;
            let data = [];
            _.find(this.explorer, (exp) => {
                if(exp.name === expName){
                    data = exp.tag[this.widget.explorer.tag];
                }
            })
            return data;
        },

        getHeaderTitle(){
            const expName = this.widget.explorer.explorer;
            let title = expName
            _.find(this.explorer, (exp) => {
                if(exp.name === expName){
                    const tags = exp.tag[this.widget.explorer.tag];
                    _.find(tags, (tag) => {
                        if(tag.name == this.widget.explorer.name){
                            title = tag.alias
                        }
                    })
                }
            })
            return title;
        }
    }
}