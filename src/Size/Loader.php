<?php
declare(strict_types=1);

namespace Size;

use pocketmine\{Server, Player};
use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender};
use pocketmine\event\{Listener, player\PlayerRespawnEvent};
use pocketmine\entity\Entity;

class Loader extends PluginBase implements Listener{
    
    /** var $size */
    public $size = array();

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
    	if(!$sender instanceof Player){
    		$this->getServer()->getLogger()->warning("You can't use command on console");
    		return true;
    	}
    	if(strtolower($cmd->getName()) === 'size'){
    	    if($sender->hasPermission("kichthuoc.command")){
                if(isset($args[0])) {
                    if(is_numeric($args[0])){
                        if($args[0] > 10) {
                            $sender->sendMessage("§cKích thước không được lớn hơn 10");
                            return true;
                        }elseif($args[0] <= 0){
                            $sender->sendMessage("§cKích thước không được nhỏ hơn 0 hoặc âm");
                            return true;
                        }
                        $this->size[$sender->getName()] = $args[0];
                        $sender->setScale((float)$args[0]);
                        $sender->sendMessage("§aBạn đã chỉnh kích thước của cơ thể thành $args[0] ");
                    }elseif($args[0] == "reset"){
                        if(!empty($this->size[$sender->getName()])) {
                            unset($this->size[$sender->getName()]);
                            $sender->setScale(1);
                            $sender->sendMessage("§aKích thước của bạn đã trở lại bình thường");
                        }else{
                            $sender->sendMessage("§aBạn đã đặt lại kích thước");
                        }
                    }else{
                        $sender->sendMessage("§b/size help §7 - dùng để xem bảng hướng dẫn thay đổi kích thước\n§b/size reset §7 - Đặt lại kích thước cơ thể!\n§b/size [kích cỡ 1->10] §7 - Chỉnh kích cỡ");
                    }
                }else{
                    $sender->sendMessage("§cKích thước phải là một số hợp lệ");
                }
            }else{
                $sender->sendMessage("§cBạn không có quyền để sử dụng các câu lệnh của /size!");
            }
        }
        return true;
    }
	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onPlayerRespawn(PlayerRespawnEvent $e): void{
        $player = $e->getPlayer();
        if(!empty($this->size[$player->getName()])){
            $size = $this->size[$player->getName()];
            $player->setScale((float)$size);
         }
     }
}